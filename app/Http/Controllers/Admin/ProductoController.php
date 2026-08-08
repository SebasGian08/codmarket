<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Proveedor;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Exports\PlantillaProductosExport;
use App\Exports\ProductosExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductoController extends Controller
{
    public function exportar()
    {
        return Excel::download(new ProductosExport(), 'Productos.xlsx');
    }

    public function index()
    {
        $productos = Producto::with(['marca', 'proveedor'])->get();
        $marcas = Marca::where('estado', 1)->get();
        $proveedores = Proveedor::where('estado', 1)->get();
        $categorias = Categoria::where('estado', 1)->get();

        return view('admin.productos.index', compact(
            'productos',
            'marcas',
            'proveedores',
            'categorias'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:200',

            // variante inicial obligatoria
            'sku_inicial' => 'required|max:100',
            'precio_inicial' => 'required|numeric|min:0',
        ]);

        try {

            DB::beginTransaction();

            $producto = Producto::create([
                'nombre' => $request->nombre,
                'slug' => Str::slug($request->nombre . '-' . uniqid()),
                'descripcion' => $request->descripcion,
                'descripcion_corta' => $request->descripcion_corta,
                'id_marca' => $request->id_marca,
                'id_proveedor' => $request->id_proveedor,
                'peso' => $request->peso,
                'dimensiones' => $request->dimensiones,
                'destacado' => $request->destacado ?? 0,
                'nuevo' => $request->nuevo ?? 0,
                'estado' => $request->estado ?? 1,
            ]);

            // categorías
            if ($request->categorias) {
                $producto->categorias()->sync($request->categorias);
            }

            // PRIMERA VARIANTE
            $producto->variantes()->create([
                'sku' => $request->sku_inicial,
                'codigo_barras' => $request->codigo_barras,
                'precio' => $request->precio_inicial,
                'precio_oferta' => $request->precio_oferta_inicial,
                'costo' => $request->costo_inicial,
                'stock' => $request->stock_inicial ?? 0,
                'estado' => 1,
            ]);

            DB::commit();

            return back()->with('success', 'Producto creado');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:200',
            'sku_principal' => 'required|max:100',
            'precio_principal' => 'required|numeric|min:0',
        ]);

        try {

            DB::beginTransaction();

            $producto = Producto::findOrFail($id);

            $producto->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'descripcion_corta' => $request->descripcion_corta,
                'id_marca' => $request->id_marca,
                'id_proveedor' => $request->id_proveedor,
                'peso' => $request->peso,
                'dimensiones' => $request->dimensiones,
                'destacado' => $request->destacado ?? 0,
                'nuevo' => $request->nuevo ?? 0,
                'estado' => $request->estado ?? 1,
            ]);

            $producto->categorias()->sync($request->categorias ?? []);

            $primeraVariante = $producto->variantes()->first();

            if ($primeraVariante) {

                $primeraVariante->update([
                    'sku' => $request->sku_principal,
                    'codigo_barras' => $request->codigo_barras,
                    'precio' => $request->precio_principal,
                    'precio_oferta' => $request->precio_oferta_principal,
                    'costo' => $request->costo_principal,
                    'stock' => $request->stock_principal ?? 0,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Producto actualizado');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return back()->with('delete', 'Producto eliminado');
    }

    public function plantilla()
    {
        return Excel::download(new PlantillaProductosExport(), 'Plantilla_Productos.xlsx');
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

            $importados = 0;

            foreach ($datos as $fila) {

                if (empty($fila[0])) {
                    continue;
                }

                $nombre = trim($fila[0] ?? '');
                $descripcion = trim($fila[1] ?? '');
                $descripcionCorta = trim($fila[2] ?? '');
                $marcaNombre = trim($fila[3] ?? '');
                $proveedorNombre = trim($fila[4] ?? '');
                $categoriasTexto = trim($fila[5] ?? '');
                $peso = $fila[6] ?? null;
                $dimensiones = $fila[7] ?? null;
                $sku = trim($fila[8] ?? '');
                $codigoBarras = trim($fila[9] ?? '');
                $precio = $fila[10] ?? 0;
                $precioOferta = $fila[11] ?? null;
                $costo = $fila[12] ?? null;
                $stock = $fila[13] ?? 0;
                $destacado = $fila[14] ?? 0;
                $nuevo = $fila[15] ?? 0;
                $estado = $fila[16] ?? 1;

                // SKU automático si viene fórmula
                if (!$sku || str_contains($sku, '=')) {
                    $sku = rand(10000000, 99999999);
                }

                // Código de barras automático si viene fórmula
                if (!$codigoBarras || str_contains($codigoBarras, '=')) {
                    $codigoBarras = rand(10000000, 99999999);
                }

                // Marca
                $marca = null;

                if ($marcaNombre) {
                    $marca = Marca::firstOrCreate(
                        ['nombre' => $marcaNombre],
                        [
                            'slug' => Str::slug($marcaNombre),
                            'estado' => 1
                        ]
                    );
                }

                // Proveedor
                $proveedor = null;

                if ($proveedorNombre) {
                    $proveedor = Proveedor::firstOrCreate(
                        ['nombre' => $proveedorNombre],
                        [
                            'estado' => 1
                        ]
                    );
                }

                // Producto
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

                // Categorías
                if ($categoriasTexto) {

                    $categoriasIds = [];

                    foreach (explode(',', $categoriasTexto) as $catNombre) {

                        $catNombre = trim($catNombre);

                        if (!$catNombre) {
                            continue;
                        }

                        $categoria = Categoria::firstOrCreate(
                            ['nombre' => $catNombre],
                            [
                                'slug' => Str::slug($catNombre),
                                'estado' => 1
                            ]
                        );

                        $categoriasIds[] = $categoria->id_categoria;
                    }

                    if (!empty($categoriasIds)) {
                        $producto->categorias()->sync($categoriasIds);
                    }
                }

                // Variante principal
                $producto->variantes()->create([
                    'sku' => $sku,
                    'codigo_barras' => $codigoBarras,
                    'precio' => $precio,
                    'precio_oferta' => $precioOferta,
                    'costo' => $costo,
                    'stock' => $stock,
                    'estado' => 1
                ]);

                $importados++;
            }

            DB::commit();

            return back()->with(
                'success',
                "Productos importados correctamente. Total: {$importados}"
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}