<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductoVariante;
use App\Models\Producto;
use App\Models\Atributo;
use App\Models\AtributoValor;
use App\Exports\PlantillaVariantesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProductoVarianteController extends Controller
{
    public function plantilla()
    {
        return Excel::download(new PlantillaVariantesExport(), 'Plantilla_Variantes.xlsx');
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

            $creadas = 0;
            $actualizadas = 0;
            $sinSku = 0;
            $sinProducto = 0;

            foreach ($datos as $fila) {

                $productoSku = trim($fila[0] ?? '');
                $sku = trim($fila[1] ?? '');

                if (!$sku) {
                    $sinSku++;
                    continue;
                }

                $producto = $this->buscarProducto($productoSku);

                if (!$producto) {
                    $sinProducto++;
                    continue;
                }

                $data = [
                    'codigo_barras' => trim($fila[2] ?? '') ?: null,
                    'precio' => $fila[3] ?? 0,
                    'precio_oferta' => $this->nuloSiVacio($fila[4] ?? null),
                    'costo' => $this->nuloSiVacio($fila[5] ?? null),
                    'stock' => $fila[6] ?? 0,
                    'estado' => $this->nuloSiVacio($fila[7] ?? null) ?? 1,
                ];

                $variante = ProductoVariante::where('sku', $sku)->first();

                if ($variante) {

                    $variante->update($data);
                    $actualizadas++;

                } else {

                    $variante = ProductoVariante::create(array_merge($data, [
                        'id_producto' => $producto->id_producto,
                        'sku' => $sku,
                    ]));

                    $creadas++;
                }

                $this->syncAtributos($variante, $fila[8] ?? null);
            }

            DB::commit();

            return back()->with(
                'success',
                "Importación de variantes completada: {$creadas} creadas, {$actualizadas} actualizadas, {$sinProducto} sin producto, {$sinSku} sin sku."
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function index($productoId)
    {
        $producto = Producto::findOrFail($productoId);

        $variantes = ProductoVariante::where('id_producto', $productoId)
            ->with('atributos')
            ->orderBy('id_variante', 'asc')
            ->get();

        $valores = AtributoValor::with('atributo')->get();

        return view('admin.variantes.index', compact(
            'producto',
            'variantes',
            'valores'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|exists:productos,id_producto',
            'sku' => 'required|max:100',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);

        try {

            DB::beginTransaction();

            $variante = ProductoVariante::create([
                'id_producto' => $request->id_producto,
                'sku' => $request->sku,
                'codigo_barras' => $request->codigo_barras,
                'precio' => $request->precio,
                'precio_oferta' => $request->precio_oferta,
                'costo' => $request->costo,
                'stock' => $request->stock,
                'estado' => $request->estado ?? 1,
            ]);

            $variante->atributos()->sync($request->valores ?? []);

            DB::commit();

            return back()->with('success', 'Variante creada');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sku' => 'required|max:100',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);

        try {

            DB::beginTransaction();

            $variante = ProductoVariante::findOrFail($id);

            $variante->update([
                'sku' => $request->sku,
                'codigo_barras' => $request->codigo_barras,
                'precio' => $request->precio,
                'precio_oferta' => $request->precio_oferta,
                'costo' => $request->costo,
                'stock' => $request->stock,
                'estado' => $request->estado ?? 1,
            ]);

            $variante->atributos()->sync($request->valores ?? []);

            DB::commit();

            return back()->with('success', 'Variante actualizada');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $variante = ProductoVariante::findOrFail($id);

        $variante->delete();

        return back()->with('delete', 'Variante eliminada');
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