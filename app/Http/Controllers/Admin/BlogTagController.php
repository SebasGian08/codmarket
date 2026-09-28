<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogTagController extends Controller
{
    public function index()
    {
        $tags = BlogTag::withCount('blogs')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $slug = $this->generarSlugUnico($request->name);

            BlogTag::create([
                'name' => $request->name,
                'slug' => $slug,
            ]);

            DB::commit();

            return redirect()->route('admin.tags.index')
                ->with('success', 'Tag creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $tag = BlogTag::findOrFail($id);
            $slug = $this->generarSlugUnico($request->name, $id);

            $tag->update([
                'name' => $request->name,
                'slug' => $slug,
            ]);

            DB::commit();

            return redirect()->route('admin.tags.index')
                ->with('success', 'Tag actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $tag = BlogTag::findOrFail($id);
        $tag->blogs()->detach();
        $tag->delete();

        return back()->with('success', 'Tag eliminado');
    }

    private function generarSlugUnico($name, $excluirId = null)
    {
        $base = Str::slug($name);
        if (empty($base)) {
            $base = 'tag';
        }

        $slug = $base;
        $contador = 1;

        while (BlogTag::where('slug', $slug)
            ->when($excluirId, fn($q) => $q->where('id_blogs_tags', '!=', $excluirId))
            ->exists()) {
            $slug = $base . '-' . $contador;
            $contador++;
        }

        return $slug;
    }
}