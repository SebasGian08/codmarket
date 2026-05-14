<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('orden', 'asc')->get();
        return view('admin.categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'icono' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            $imagen = null;

            if ($request->hasFile('imagen')) {
                $imagen = uploadImageOptimized($request->file('imagen'), 'categorias');
            }

            Categoria::create([
                'nombre' => $request->nombre,
                'slug' => Str::slug($request->nombre),
                'descripcion' => $request->descripcion,
                'imagen' => $imagen,
                'icono' => $request->icono,
                'id_categoria_padre' => $request->id_categoria_padre,
                'orden' => $request->orden ?? 0,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.categorias.index')
                ->with('success', 'Categoría creada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'icono' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            $categoria = Categoria::findOrFail($id);

            $imagen = $categoria->imagen;

            if ($request->hasFile('imagen')) {
                if ($categoria->imagen) {
                    $this->deleteImage($categoria->imagen);
                }
                $imagen = uploadImageOptimized($request->file('imagen'), 'categorias');
            }

            $categoria->update([
                'nombre' => $request->nombre,
                'slug' => Str::slug($request->nombre),
                'descripcion' => $request->descripcion,
                'imagen' => $imagen,
                'icono' => $request->icono,
                'id_categoria_padre' => $request->id_categoria_padre,
                'orden' => $request->orden ?? 0,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.categorias.index')
                ->with('success', 'Categoría actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);

        if ($categoria->imagen) {
            $this->deleteImage($categoria->imagen);
        }

        $categoria->delete();

        return back()->with('success', 'Categoría eliminada');
    }

    private function deleteImage($path)
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}