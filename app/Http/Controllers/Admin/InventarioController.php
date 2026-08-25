<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Tienda;

class InventarioController extends Controller
{
    public function index()
    {
        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();

        $productos = Producto::with([
            'variantes.atributos.atributo',
            'variantes.imagenes',
            'imagenes'
        ])
            ->where('estado', 1)
            ->orderBy('nombre', 'asc')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id_producto,
                    'nombre' => $p->nombre,
                    'imagen' => $p->imagen_principal_url,
                    'variantes' => $p->variantes->map(function ($v) {
                        $img = $v->imagenes->where('principal', 1)->first() ?? $v->imagenes->first();
                        return [
                            'id' => $v->id_variante,
                            'sku' => $v->sku,
                            'precio' => $v->precio,
                            'precio_oferta' => $v->precio_oferta,
                            'costo' => $v->costo,
                            'estado' => $v->estado,
                            'imagen' => $img ? asset($img->url) : null,
                            'atributos' => $v->atributos->map(function ($a) {
                                return [
                                    'atributo' => $a->atributo->nombre ?? '',
                                    'valor' => $a->valor,
                                ];
                            })->values(),
                        ];
                    })->values(),
                ];
            });

        $stockPorTienda = Inventario::get()
            ->groupBy('id_variante')
            ->map(function ($grupo) {
                return $grupo->pluck('cantidad', 'id_tienda')->toArray();
            })
            ->toArray();

        return view('admin.inventarios.index', compact(
            'tiendas',
            'productos',
            'stockPorTienda'
        ));
    }
}
