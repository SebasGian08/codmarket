<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.blogs.index', ['tab' => 'categorias']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
        ]);

        try {
            DB::beginTransaction();

            BlogsCategory::create([
                'name' => $request->name,
                'slug' => $this->generarSlugUnico($request->name),
            ]);

            DB::commit();

            return redirect()->route('admin.blogs.index', ['tab' => 'categorias'])
                ->with('success', 'Categoría creada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:200',
        ]);

        try {
            DB::beginTransaction();

            $category = BlogsCategory::findOrFail($id);
            $category->update([
                'name' => $request->name,
                'slug' => $this->generarSlugUnico($request->name, $id),
            ]);

            DB::commit();

            return redirect()->route('admin.blogs.index', ['tab' => 'categorias'])
                ->with('success', 'Categoría actualizada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $category = BlogsCategory::withCount('blogs')->findOrFail($id);

        if ($category->blogs_count > 0) {
            return back()->with('error', 'No se puede eliminar una categoría que tiene blogs asociados.');
        }

        $category->delete();

        return back()->with('success', 'Categoría eliminada');
    }

    private function generarSlugUnico($name, $excluirId = null)
    {
        $base = Str::slug($name);
        if (empty($base)) {
            $base = 'categoria';
        }

        $slug = $base;
        $contador = 1;

        while (BlogsCategory::where('slug', $slug)
            ->when($excluirId, fn($query) => $query->where('id_blogs_categories', '!=', $excluirId))
            ->exists()) {
            $slug = $base . '-' . $contador;
            $contador++;
        }

        return $slug;
    }
}
