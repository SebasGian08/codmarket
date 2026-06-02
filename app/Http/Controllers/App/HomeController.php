<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Blog;
use App\Models\BannerPrincipal;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Promocion;
use App\Models\Empresa;
use App\Models\Marca;
use App\Models\TrabajoRealizado;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('estado', 1)->get();

        $blogs = Blog::with('category')
            ->where('status', 1)
            ->latest('id_blog')
            ->take(6)
            ->get();

        $now = now();

        $categorias = Categoria::where('estado', 1)
            ->orderBy('orden', 'asc')
            ->get();


        $categoriasProductos = Categoria::with([
                'productos' => function ($q) {
                    $q->with([
                            'marca',
                            'variantes.imagenes',
                            'imagenes',
                            'categorias'
                        ])
                        ->where('estado', 1)
                        ->latest('id_producto')
                        ->take(10);
                }
            ])
            ->where('estado', 1)
            ->orderBy('orden', 'asc')
            ->get();

        $banners = BannerPrincipal::where('estado', 1)
            ->where(function ($q) use ($now) {
                $q->whereNull('fecha_inicio')->orWhere('fecha_inicio', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', $now);
            })
            ->orderBy('orden')
            ->get();

        $productos = Producto::with([
                'marca',
                'variantes.imagenes',
                'imagenes',
                'categorias'
            ])
            ->where('estado', 1)
            ->latest('id_producto')
            ->take(10)
            ->get();

        $promociones = Promocion::where('estado', 1)
            ->where(function ($q) use ($now) {
                $q->whereNull('fecha_inicio')
                ->orWhere('fecha_inicio', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('fecha_fin')
                ->orWhere('fecha_fin', '>=', $now);
            })
            ->orderBy('orden', 'asc')
            ->get();

        $marcas = Marca::where('estado', 1)
            ->orderBy('orden', 'asc')
            ->get();

        $trabajosRealizados = TrabajoRealizado::where('estado', 1)
            ->orderBy('orden', 'asc')
            ->get();
                
        return view('pages.home', compact(
            'services',
            'blogs',
            'banners',
            'categorias',
            'productos',
            'categoriasProductos',
            'promociones',
            'marcas',
            'trabajosRealizados'
        ));
    }

    public function store(Request $request)
    {
        return back()->with('success', 'Mensaje enviado');
    }

    public function nosotros()
    {
        $empresa = Empresa::first();

        return view('pages.nosotros.index', compact('empresa'));
    }
}