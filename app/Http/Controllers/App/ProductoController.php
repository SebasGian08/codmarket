<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\Marca;
use App\Models\Categoria;

class ProductoController extends Controller
{
    public function show($slug, Request $request)
    {
        $producto = Producto::with([
            'marca',
            'variantes.imagenes',
            'variantes.atributos.atributo',
            'imagenes',
            'categorias'
        ])->where('slug', $slug)->firstOrFail();

        $categorias = $producto->categorias ?? collect();

        $variantes = $producto->variantes;

        if ($variantes->isEmpty()) {
            abort(404, 'Producto sin variantes configuradas');
        }

        $varianteActiva = $request->variante
            ? $variantes->firstWhere('id_variante', $request->variante)
            : $variantes->first();

        $imagenes = $varianteActiva->imagenes()
            ->orderBy('orden')
            ->get();

        $relacionados = Producto::with(['imagenes', 'marca'])
            ->where('id_producto', '!=', $producto->id_producto)
            ->where(function ($q) use ($categorias, $producto) {
                $q->whereHas('categorias', function ($q2) use ($categorias) {
                    $q2->whereIn('categorias.id_categoria', $categorias->pluck('id_categoria'));
                })
                ->orWhere('id_marca', $producto->id_marca);
            })
            ->take(8)
            ->get();

        return view('pages.producto.show', compact(
            'producto',
            'varianteActiva',
            'variantes',
            'categorias',
            'imagenes',
            'relacionados'
        ));
    }

    public function variante($slug, $varianteId)
    {
        $producto = Producto::with([
            'variantes.imagenes',
            'variantes.atributos.atributo',
        ])->where('slug', $slug)->firstOrFail();

        $variante = $producto->variantes->firstWhere('id_variante', $varianteId);

        if (!$variante) {
            return response()->json(['error' => 'Variante no encontrada'], 404);
        }

        $precioRegular = (float) $variante->precio;
        $precioPromo = $variante->precio_oferta !== null ? (float) $variante->precio_oferta : $precioRegular;

        $descuento = $precioRegular > 0
            ? round((($precioRegular - $precioPromo) / $precioRegular) * 100)
            : 0;

        $imagenes = $variante->imagenes()
            ->orderBy('orden')
            ->get()
            ->map(function ($img) {
                return [
                    'id' => $img->id_imagen,
                    'url' => asset($img->url),
                    'principal' => (int) $img->principal,
                ];
            })->values();

        return response()->json([
            'id' => (int) $variante->id_variante,
            'sku' => $variante->sku,
            'precio' => $precioRegular,
            'precio_oferta' => $variante->precio_oferta !== null ? $precioPromo : null,
            'descuento' => $descuento,
            'imagenes' => $imagenes,
            'valores_ids' => $variante->atributos
                ->pluck('id_valor')
                ->map(function ($v) {
                    return (int) $v;
                })
                ->all(),
            'url' => route('producto.show', $slug) . '?variante=' . $variante->id_variante,
        ]);
    }

    public function index(Request $request)
    {
        $query = Producto::with([
            'marca',
            'categorias',
            'variantes.imagenes'
        ])->where('estado', 1);

        // FILTRO CATEGORÍA
        if ($request->categoria) {
            $query->whereHas('categorias', function ($q) use ($request) {
                $q->where('categorias.slug', $request->categoria);
            });
        }

        // FILTRO MARCA
        if ($request->marca) {
            $query->whereHas('marca', function ($q) use ($request) {
                $q->where('slug', $request->marca);
            });
        }

        // FILTRO PRECIO
        if ($request->min) {
            $query->whereHas('variantes', function ($q) use ($request) {
                $q->where('precio_oferta', '>=', $request->min);
            });
        }

        if ($request->max) {
            $query->whereHas('variantes', function ($q) use ($request) {
                $q->where('precio_oferta', '<=', $request->max);
            });
        }

        // FILTRO BUSCADOR
        if ($request->search) {
            $texto = $request->search;

            $query->where(function ($q) use ($texto) {
                $q->where('nombre', 'LIKE', "%{$texto}%")
                ->orWhere('descripcion', 'LIKE', "%{$texto}%")
                ->orWhere('descripcion_corta', 'LIKE', "%{$texto}%");
            });
        }
        $productos = $query->latest()->paginate(12);

        $categorias = Categoria::where('estado', 1)->get();
        $marcas = Marca::where('estado', 1)->get();

        return view('pages.producto.index', compact(
            'productos',
            'categorias',
            'marcas'
        ));
    }

    public function buscar(Request $request)
    {
        $texto = $request->search;

        $productos = Producto::with([
                'marca',
                'categorias',
                'variantes.imagenes'
            ])
            ->where(function ($q) use ($texto) {

                $q->where('nombre', 'LIKE', "%{$texto}%")
                ->orWhere('descripcion', 'LIKE', "%{$texto}%")
                ->orWhere('descripcion_corta', 'LIKE', "%{$texto}%");

            })
            ->where('estado', 1)
            ->latest()
            ->paginate(12);

        $categorias = Categoria::where('estado', 1)->get();

        $marcas = Marca::where('estado', 1)->get();

        return view('pages.producto.index', compact(
            'productos',
            'texto',
            'categorias',
            'marcas'
        ));
    }

    public function categoria($slug)
    {
        $categoria = Categoria::where('slug', $slug)->firstOrFail();

        $productos = Producto::with([
                'marca',
                'categorias',
                'variantes.imagenes'
            ])
            ->whereHas('categorias', function ($q) use ($categoria) {
                $q->where('categorias.id_categoria', $categoria->id_categoria);
            })
            ->where('estado', 1)
            ->latest()
            ->paginate(12);

        $categorias = Categoria::where('estado', 1)->get();

        $marcas = Marca::where('estado', 1)->get();

        return view('pages.producto.index', [
            'productos' => $productos,
            'texto' => $categoria->nombre,
            'categorias' => $categorias,
            'marcas' => $marcas
        ]);
    }
}