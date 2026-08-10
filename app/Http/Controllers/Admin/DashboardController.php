<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\ProductoVariante;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- KPIs generales ---
        $totalProductos = Producto::count();
        $productosActivos = Producto::where('estado', 1)->count();
        $totalVariantes = ProductoVariante::count();

        $stockTotal = (int) ProductoVariante::sum('stock');

        $agotados = ProductoVariante::where('stock', '<=', 0)->count();
        $stockBajo = ProductoVariante::where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->count();

        $valorInventarioCosto = (float) ProductoVariante::where('stock', '>', 0)
            ->sum(DB::raw('COALESCE(costo, 0) * stock'));
        $valorInventarioVenta = (float) ProductoVariante::where('stock', '>', 0)
            ->sum(DB::raw('COALESCE(precio, 0) * stock'));

        $destacados = Producto::where('destacado', 1)->count();
        $nuevos = Producto::where('nuevo', 1)->count();

        // --- Gráficos ---
        $porCategoria = Categoria::select('id_categoria', 'nombre')
            ->withCount('productos')
            ->orderBy('productos_count', 'desc')
            ->limit(8)
            ->get();

        $porMarca = Producto::select('marcas.nombre', DB::raw('COUNT(productos.id_producto) as total'))
            ->leftJoin('marcas', 'marcas.id_marca', '=', 'productos.id_marca')
            ->whereNotNull('productos.id_marca')
            ->groupBy('marcas.id_marca', 'marcas.nombre')
            ->orderBy('total', 'desc')
            ->limit(8)
            ->get();

        $porEstadoStock = [
            'stockDisponible' => ProductoVariante::where('stock', '>', 5)->count(),
            'stockBajo'       => $stockBajo,
            'agotados'        => $agotados,
        ];

        // --- Alertas y listados ---
        $alertasStock = ProductoVariante::with('producto')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->limit(15)
            ->get();

        $ultimosProductos = Producto::with('marca')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalProductos',
            'productosActivos',
            'totalVariantes',
            'stockTotal',
            'agotados',
            'stockBajo',
            'valorInventarioCosto',
            'valorInventarioVenta',
            'destacados',
            'nuevos',
            'porCategoria',
            'porMarca',
            'porEstadoStock',
            'alertasStock',
            'ultimosProductos'
        ));
    }
}
