<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Atributo;
use App\Models\AtributoValor;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\ProductoVariante;
use App\Models\Proveedor;
use App\Exports\PlantillaCargaProductosExport;
use App\Services\InventarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CargaProductosController extends Controller
{
    protected $inventario;

    public function __construct(InventarioService $inventario)
    {
        $this->inventario = $inventario;
    }

    public function index()
    {
        return view('admin.productos.carga');
    }

    public function plantilla()
    {
        return Excel::download(new PlantillaCargaProductosExport(), 'Plantilla_Productos_Variantes.xlsx');
    }

    private function tiendaPorDefecto()
    {
        $tienda = \App\Models\Tienda::where('es_principal', 1)->first()
            ?? \App\Models\Tienda::first();

        return $tienda ? $tienda->id_tienda : null;
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls'
        ]);

        try {

            DB::beginTransaction();

            $rows = Excel::toArray([], $request->file('archivo'));

            if (empty($rows) || empty($rows[0])) {
                throw new \Exception('El archivo no contiene datos.');
            }

            $datos = $rows[0];

            unset($datos[0]); // eliminar cabecera

            $productosCreados = 0;
            $variantesCreadas = 0;
            $variantesActualizadas = 0;
            $sinProducto = 0;

            $tiendaId = $this->tiendaPorDefecto();

            foreach ($datos as $fila) {

                $nombre = trim($fila[0] ?? '');
                $productoSku = trim($fila[8] ?? '');
                $sku = trim($fila[9] ?? '');

                // Producto: buscar por referencia (producto_sku) o nombre
                $producto = $this->buscarProducto($productoSku ?: $nombre);

                if (!$producto) {

                    if (!$nombre) {
                        $sinProducto++;
                        continue;
                    }

                    $producto = $this->crearProducto($fila);
                    $productosCreados++;
                }

                if (!$sku || str_contains($sku, '=')) {
                    $sku = rand(10000000, 99999999);
                }

                $codigoBarras = trim($fila[10] ?? '');
                if (!$codigoBarras || str_contains($codigoBarras, '=')) {
                    $codigoBarras = rand(10000000, 99999999);
                }

                $precio = $fila[11] ?? 0;
                $precioOferta = $this->nuloSiVacio($fila[12] ?? null);
                $costo = $this->nuloSiVacio($fila[13] ?? null);
                $stock = (int) ($fila[14] ?? 0);
                $estado = $this->nuloSiVacio($fila[17] ?? null) ?? 1;

                $variante = ProductoVariante::where('sku', $sku)->first();

                if ($variante) {

                    $stockAnterior = (int) $variante->stock;

                    $variante->update([
                        'codigo_barras' => $codigoBarras,
                        'precio' => $precio,
                        'precio_oferta' => $precioOferta,
                        'costo' => $costo,
                        'estado' => $estado,
                    ]);

                    if ($stock !== $stockAnterior && $tiendaId) {
                        $this->inventario->aplicar(
                            $variante->id_variante,
                            $tiendaId,
                            'ajuste',
                            $stock - $stockAnterior,
                            null,
                            auth()->id(),
                            'Carga masiva de productos: ' . $sku . ' stock ' . $stockAnterior . ' -> ' . $stock
                        );
                    }

                    $variantesActualizadas++;

                } else {

                    $variante = ProductoVariante::create([
                        'id_producto' => $producto->id_producto,
                        'sku' => $sku,
                        'codigo_barras' => $codigoBarras,
                        'precio' => $precio,
                        'precio_oferta' => $precioOferta,
                        'costo' => $costo,
                        'stock' => 0,
                        'estado' => $estado,
                    ]);

                    if ($stock > 0 && $tiendaId) {
                        $this->inventario->aplicar(
                            $variante->id_variante,
                            $tiendaId,
                            'ingreso',
                            $stock,
                            null,
                            auth()->id(),
                            'Carga masiva de productos: stock inicial de ' . $sku
                        );
                    }

                    $variantesCreadas++;
                }

                $this->syncAtributos($variante, $fila[18] ?? null);
            }

            DB::commit();

            return back()->with(
                'success',
                "Carga masiva completada: {$productosCreados} productos creados, {$variantesCreadas} variantes creadas, {$variantesActualizadas} variantes actualizadas, {$sinProducto} filas sin producto."
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    private function crearProducto($fila)
    {
        $nombre = trim($fila[0] ?? '');
        $descripcion = trim($fila[1] ?? '');
        $descripcionCorta = trim($fila[2] ?? '');
        $marcaNombre = trim($fila[3] ?? '');
        $proveedorNombre = trim($fila[4] ?? '');
        $categoriasTexto = trim($fila[5] ?? '');
        $peso = $fila[6] ?? null;
        $dimensiones = $fila[7] ?? null;
        $destacado = $fila[15] ?? 0;
        $nuevo = $fila[16] ?? 0;
        $estado = $this->nuloSiVacio($fila[17] ?? null) ?? 1;

        $marca = null;
        if ($marcaNombre) {
            $marca = Marca::firstOrCreate(
                ['nombre' => $marcaNombre],
                ['slug' => Str::slug($marcaNombre), 'estado' => 1]
            );
        }

        $proveedor = null;
        if ($proveedorNombre) {
            $proveedor = Proveedor::firstOrCreate(
                ['nombre' => $proveedorNombre],
                ['estado' => 1]
            );
        }

        $producto = Producto::create([
            'nombre' => $nombre,
            'slug' => Str::slug($nombre . '-' . uniqid()),
            'descripcion' => $descripcion,
            'descripcion_corta' => $descripcionCorta,
            'id_marca' => $marca ? $marca->id_marca : null,
            'id_proveedor' => $proveedor ? $proveedor->id_proveedor : null,
            'peso' => $peso,
            'dimensiones' => $dimensiones,
            'destacado' => $destacado,
            'nuevo' => $nuevo,
            'estado' => $estado,
        ]);

        if ($categoriasTexto) {
            $categoriasIds = [];
            foreach (explode(',', $categoriasTexto) as $catNombre) {
                $catNombre = trim($catNombre);
                if (!$catNombre) {
                    continue;
                }
                $categoria = Categoria::firstOrCreate(
                    ['nombre' => $catNombre],
                    ['slug' => Str::slug($catNombre), 'estado' => 1]
                );
                $categoriasIds[] = $categoria->id_categoria;
            }
            if (!empty($categoriasIds)) {
                $producto->categorias()->sync($categoriasIds);
            }
        }

        return $producto;
    }

    private function buscarProducto($referencia)
    {
        $variante = ProductoVariante::where('sku', $referencia)->first();

        if ($variante && $variante->producto) {
            return $variante->producto;
        }

        $producto = Producto::where('nombre', $referencia)->first();

        if ($producto) {
            return $producto;
        }

        return Producto::where('slug', Str::slug($referencia))->first();
    }

    private function nuloSiVacio($valor)
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        return $valor;
    }

    private function syncAtributos($variante, $texto)
    {
        if (!$texto || !trim($texto)) {
            return;
        }

        $valoresIds = [];

        foreach (explode(',', $texto) as $par) {

            $partes = explode(':', $par);

            if (count($partes) < 2) {
                continue;
            }

            $atributoNombre = trim($partes[0]);
            $valorNombre = trim(implode(':', array_slice($partes, 1)));

            if (!$atributoNombre || !$valorNombre) {
                continue;
            }

            $atributo = Atributo::firstOrCreate(['nombre' => $atributoNombre]);

            $valor = AtributoValor::firstOrCreate([
                'id_atributo' => $atributo->id_atributo,
                'valor' => $valorNombre,
            ]);

            $valoresIds[] = $valor->id_valor;
        }

        if (!empty($valoresIds)) {
            $variante->atributos()->sync($valoresIds);
        }
    }
}
