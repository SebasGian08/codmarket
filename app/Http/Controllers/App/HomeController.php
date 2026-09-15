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
use App\Models\Cliente;
use App\Models\TrabajoRealizado;
use App\Models\Rubro;
use App\Models\PreguntaFrecuente;

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

        $categorias = Categoria::whereNull('id_categoria_padre')
            ->with(['hijos' => function ($q) {
                $q->where('estado', 1)->orderBy('orden', 'asc');
            }])
            ->where('estado', 1)
            ->orderBy('orden', 'asc')
            ->get();

        $allCategorias = Categoria::where('estado', 1)
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

        $rubros = Rubro::where('estado', 1)
            ->orderBy('orden', 'asc')
            ->get();
                
        $clientes = Cliente::where('estado', 1)
            ->orderBy('nombre', 'asc')
            ->get();

        $preguntas = PreguntaFrecuente::where('estado', 1)
            ->orderBy('orden', 'asc')
            ->get();

        return view('pages.home', compact(
            'services',
            'blogs',
            'banners',
            'categorias',
            'allCategorias',
            'productos',
            'categoriasProductos',
            'promociones',
            'marcas',
            'trabajosRealizados',
            'rubros',
            'clientes',
            'preguntas'
        ));
    }

    public function store(Request $request)
    {
        return back()->with('success', 'Mensaje enviado');
    }

    public function nosotros()
    {
        $empresa = Empresa::first();
        $indicadores = $empresa->empresa_indicadores ?: [
            ['valor' => $empresa->indicador_1_valor ?? '+10', 'titulo' => $empresa->indicador_1_titulo ?? 'Años de experiencia'],
            ['valor' => $empresa->indicador_2_valor ?? '100%', 'titulo' => $empresa->indicador_2_titulo ?? 'Compromiso profesional'],
            ['valor' => $empresa->indicador_3_valor ?? '360°', 'titulo' => $empresa->indicador_3_titulo ?? 'Soluciones integrales'],
            ['valor' => $empresa->indicador_4_valor ?? 'ISO', 'titulo' => $empresa->indicador_4_titulo ?? 'Estándares internacionales'],
        ];

        return view('pages.nosotros.index', compact('empresa', 'indicadores'));
    }
}
