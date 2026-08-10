<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\ProductoImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoImagenController extends Controller
{
    public function index($productoId)
    {
        $producto = Producto::findOrFail($productoId);

        $imagenes = ProductoImagen::where('id_producto', $productoId)
            ->orderBy('orden', 'asc')
            ->get();

        return view('admin.productos.imagenes.index', compact(
            'producto',
            'imagenes'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|exists:productos,id_producto',
            'imagen' => 'required|image',
        ]);

        try {
            DB::beginTransaction();

            $imagen = uploadImageOptimized($request->file('imagen'), 'productos');

            if ($request->principal == 1) {
                ProductoImagen::where('id_producto', $request->id_producto)
                    ->update(['principal' => 0]);
            }

            $img = ProductoImagen::create([
                'id_producto' => $request->id_producto,
                'id_variante' => $request->id_variante,
                'url' => $imagen,
                'principal' => $request->principal ?? 0,
                'orden' => $request->orden ?? 0,
            ]);

            DB::commit();

            if ($request->wantsJson()) {
                $img->load('variante');

                return response()->json([
                    'success' => true,
                    'image' => [
                        'id' => $img->id_imagen,
                        'url' => asset($img->url),
                        'sku' => $img->variante->sku ?? 'Sin SKU',
                        'principal' => (int) $img->principal,
                        'delete_url' => route('admin.producto_imagen.destroy', [
                            'producto' => $img->id_producto,
                            'id' => $img->id_imagen,
                        ]),
                    ],
                ]);
            }

            return back()->with('success', 'Imagen subida correctamente');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'imagen' => 'nullable|image',
            ]);

            $img = ProductoImagen::findOrFail($id);

            $ruta = $img->url;

            if ($request->hasFile('imagen')) {

                if ($img->url && file_exists(public_path($img->url))) {
                    unlink(public_path($img->url));
                }

                $ruta = uploadImageOptimized($request->file('imagen'), 'productos');
            }

            if ($request->principal == 1) {
                ProductoImagen::where('id_producto', $img->id_producto)
                    ->update(['principal' => 0]);
            }

            $img->update([
                'url' => $ruta,
                'principal' => $request->principal ?? 0,
                'orden' => $request->orden ?? 0,
                'id_variante' => $request->id_variante,
            ]);

            DB::commit();

            return back()->with('success', 'Imagen actualizada');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function destroy($producto, $id)
    {
        $img = ProductoImagen::where('id_imagen', $id)
            ->where('id_producto', $producto)
            ->first();

        if (!$img) {
            return response()->json([
                'success' => false,
                'message' => 'Imagen no encontrada'
            ], 404);
        }

        if ($img->url && file_exists(public_path($img->url))) {
            unlink(public_path($img->url));
        }

        $img->delete();

        return response()->json([
            'success' => true
        ]);
    }
}