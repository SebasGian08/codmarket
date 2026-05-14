<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogsCategory;
use App\Models\BlogTag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('category', 'tags')->latest()->get();
        $categories = BlogsCategory::all();
        $tags = BlogTag::all();

        return view('admin.blog.index', compact('blogs', 'categories', 'tags'));
    }

    /**
     * Subir imagen a /uploads/blog
     */
    private function uploadImage($file, $folder = 'blog')
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $destinationPath = base_path('uploads/' . $folder);

        // Crear carpeta si no existe
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $file->move($destinationPath, $fileName);

        return 'uploads/' . $folder . '/' . $fileName;
    }

    /**
     * Crear Blog
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'title' => 'required|max:255',
            'excerpt' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:blogs_categories,id_blogs_categories',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // SUBIR IMAGEN
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request->file('image'));
        }

        $blog = Blog::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title . '-' . time()),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'image' => $imagePath,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'status' => 1
        ]);

        // TAGS
        if ($request->tags) {
            $blog->tags()->sync($request->tags);
        }

        return back()->with('success', 'Blog creado correctamente');
    }

    /**
     * Actualizar Blog
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'excerpt' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // MANTENER IMAGEN ACTUAL
        $imagePath = $blog->image;

        // SI SUBE NUEVA IMAGEN
        if ($request->hasFile('image')) {

            // eliminar imagen anterior
            if ($blog->image && file_exists(base_path($blog->image))) {
                unlink(base_path($blog->image));
            }

            $imagePath = $this->uploadImage($request->file('image'));
        }

        $blog->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title . '-' . time()),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'image' => $imagePath,
            'category_id' => $request->category_id,
            'status' => $request->status ?? 1
        ]);

        // TAGS
        if ($request->tags) {
            $blog->tags()->sync($request->tags);
        }

        return back()->with('success', 'Blog actualizado correctamente');
    }

    /**
     * Eliminar Blog
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        // eliminar imagen
        if ($blog->image && file_exists(base_path($blog->image))) {
            unlink(base_path($blog->image));
        }

        $blog->delete();

        return back()->with('delete', 'Blog eliminado correctamente');
    }
}