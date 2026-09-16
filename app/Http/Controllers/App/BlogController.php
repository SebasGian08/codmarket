<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Service;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::where('status', 1);

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                ->orWhere('excerpt', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $blogs = $query->orderBy('id_blog', 'desc')->get();

        return view('pages.blog.index', compact('blogs'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $services = Service::where('estado', 1)->get();
        $recentBlogs = Blog::where('status', 1)
            ->where('id_blog', '!=', $blog->id_blog)
            ->latest()
            ->take(3)
            ->get();

        $relatedBlogs = Blog::where('status', 1)
            ->where('id_blog', '!=', $blog->id_blog)
            ->when($blog->category_id, function ($query) use ($blog) {
                $query->where('category_id', $blog->category_id);
            })
            ->latest()
            ->take(6)
            ->get();

        if ($relatedBlogs->count() < 6) {
            $additionalBlogs = Blog::where('status', 1)
                ->where('id_blog', '!=', $blog->id_blog)
                ->whereNotIn('id_blog', $relatedBlogs->pluck('id_blog'))
                ->latest()
                ->take(6 - $relatedBlogs->count())
                ->get();

            $relatedBlogs = $relatedBlogs->concat($additionalBlogs);
        }

        return view('pages.blog.blog-detail', compact('blog', 'recentBlogs', 'relatedBlogs', 'services'));
    }
}
