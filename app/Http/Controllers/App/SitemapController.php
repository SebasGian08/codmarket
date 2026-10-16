<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Service;

class SitemapController extends Controller
{
    /**
     * Sitemap XML dinámico con las URLs públicas indexables.
     */
    public function index()
    {
        $urls = [];

        $urls[] = ['loc' => url('/'), 'changefreq' => 'daily', 'priority' => '1.0'];
        $urls[] = ['loc' => route('nosotros'), 'changefreq' => 'monthly', 'priority' => '0.7'];
        $urls[] = ['loc' => route('services.index'), 'changefreq' => 'weekly', 'priority' => '0.8'];
        $urls[] = ['loc' => route('productos.index'), 'changefreq' => 'daily', 'priority' => '0.9'];
        $urls[] = ['loc' => route('blog.index'), 'changefreq' => 'weekly', 'priority' => '0.7'];
        $urls[] = ['loc' => route('portafolio.index'), 'changefreq' => 'monthly', 'priority' => '0.6'];
        $urls[] = ['loc' => route('contact.index'), 'changefreq' => 'monthly', 'priority' => '0.6'];

        foreach (Service::where('estado', 1)->get() as $service) {
            $urls[] = [
                'loc' => route('services.show', $service->slug),
                'lastmod' => optional($service->updated_at)->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        foreach (Producto::where('estado', 1)->get() as $producto) {
            $urls[] = [
                'loc' => route('producto.show', $producto->slug),
                'lastmod' => optional($producto->updated_at)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        foreach (Categoria::where('estado', 1)->get() as $categoria) {
            $urls[] = [
                'loc' => route('productos.categoria', $categoria->slug),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ];
        }

        foreach (Blog::where('status', 1)->get() as $blog) {
            $urls[] = [
                'loc' => route('blog.show', $blog->slug),
                'lastmod' => optional($blog->updated_at)->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        return response()
            ->view('pages.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * robots.txt dinámico apuntando al sitemap absoluto.
     */
    public function robots()
    {
        $lineas = [
            'User-agent: *',
            'Disallow: /admin',
            'Allow: /',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
        ];

        return response(implode("\n", $lineas), 200)
            ->header('Content-Type', 'text/plain');
    }
}
