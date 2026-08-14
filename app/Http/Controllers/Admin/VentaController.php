<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;

class VentaController extends Controller
{
    public function index()
    {
        $productos = Producto::with(['variantes.atributos.atributo'])
            ->where('estado', 1)
            ->orderBy('nombre', 'asc')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id_producto,
                    'nombre' => $p->nombre,
                    'variantes' => $p->variantes
                        ->where('estado', 1)
                        ->values()
                        ->map(function ($v) {
                            return [
                                'id' => $v->id_variante,
                                'sku' => $v->sku,
                                'precio' => (float) $v->precio,
                                'precio_oferta' => $v->precio_oferta !== null ? (float) $v->precio_oferta : null,
                                'stock' => (int) $v->stock,
                                'atributos' => $v->atributos
                                    ->map(function ($av) {
                                        return [
                                            'atributo' => $av->atributo->nombre ?? 'Atributo',
                                            'valor' => $av->valor,
                                        ];
                                    })
                                    ->values(),
                            ];
                        }),
                ];
            })
            ->values();

        $clientes = [
            ['id' => 1, 'nombre' => 'CLIENTES VARIOS'],
            ['id' => 2, 'nombre' => 'Cliente de Prueba 1'],
            ['id' => 3, 'nombre' => 'Cliente de Prueba 2'],
            ['id' => 4, 'nombre' => 'Cliente de Prueba 3'],
        ];

        return view('admin.ventas.index', compact('productos', 'clientes'));
    }
}
