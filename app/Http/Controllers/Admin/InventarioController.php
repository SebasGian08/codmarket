<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\ProductoVariante;
use App\Models\Tienda;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();

        $idTienda = $request->get('id_tienda');
        $buscar = $request->get('buscar');

        $query = Inventario::with(['variante.producto', 'tienda'])
            ->join('productos_variantes', 'inventarios.id_variante', '=', 'productos_variantes.id_variante')
            ->join('productos', 'productos_variantes.id_producto', '=', 'productos.id_producto')
            ->select('inventarios.*')
            ->orderBy('productos.nombre', 'asc')
            ->orderBy('inventarios.id_tienda', 'asc');

        if ($idTienda) {
            $query->where('inventarios.id_tienda', $idTienda);
        }

        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('productos.nombre', 'like', '%' . $buscar . '%')
                    ->orWhere('productos_variantes.sku', 'like', '%' . $buscar . '%');
            });
        }

        $inventarios = $query->paginate(30)->appends($request->query());

        $totalVariantes = ProductoVariante::where('estado', 1)->count();

        $stockValorizado = Inventario::join('productos_variantes', 'inventarios.id_variante', '=', 'productos_variantes.id_variante')
            ->selectRaw('SUM(inventarios.cantidad * COALESCE(productos_variantes.costo, 0)) as total')
            ->value('total') ?? 0;

        return view('admin.inventarios.index', compact(
            'tiendas',
            'idTienda',
            'buscar',
            'inventarios',
            'totalVariantes',
            'stockValorizado'
        ));
    }
}
