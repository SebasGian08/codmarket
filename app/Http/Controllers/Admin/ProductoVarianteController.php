<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductoVariante;
use App\Models\Producto;
use App\Models\AtributoValor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoVarianteController extends Controller
{
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
}