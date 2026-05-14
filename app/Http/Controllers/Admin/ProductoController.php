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

class ProductoController extends Controller
{
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
}