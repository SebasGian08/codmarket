<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Inventario;
use App\Models\MetodoPago;
use App\Models\Producto;
use App\Models\Tienda;
use App\Models\Vendedor;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Services\InventarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    protected $inventario;

    public function __construct(InventarioService $inventario)
    {
        $this->inventario = $inventario;
    }

    public function index()
    {
        $productos = Producto::with(['variantes.atributos.atributo', 'imagenes'])
            ->where('estado', 1)
            ->orderBy('nombre', 'asc')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id_producto,
                    'nombre' => $p->nombre,
                    'imagen' => $p->imagen_principal_url,
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

        $clientes = Cliente::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $cajasAbiertas = Caja::with(['tienda', 'vendedor'])
            ->where('estado', 1)
            ->orderBy('id_caja', 'asc')
            ->get();
        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $metodosPagos = MetodoPago::where('estado', 1)->orderBy('id_metodo_pago', 'asc')->get();
        $clienteVarios = Cliente::where('es_varios', 1)->where('estado', 1)->first();

        // Stock por tienda: { id_variante: { id_tienda: cantidad } }
        $stockPorTienda = Inventario::get()
            ->groupBy('id_variante')
            ->map(function ($grupo) {
                return $grupo->pluck('cantidad', 'id_tienda')->toArray();
            })
            ->toArray();

        return view('admin.ventas.index', compact(
            'productos',
            'clientes',
            'cajasAbiertas',
            'tiendas',
            'metodosPagos',
            'clienteVarios',
            'stockPorTienda'
        ));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'id_caja' => 'required|exists:cajas,id_caja',
            'id_cliente' => 'nullable|exists:clientes,id_cliente',
            'nombre_cliente' => 'nullable|string|max:150',
            'id_metodo_pago' => 'required|exists:metodos_pagos,id_metodo_pago',
            'items' => 'required|array|min:1',
            'items.*.id_variante' => 'required|exists:productos_variantes,id_variante',
            'items.*.cantidad' => 'required|numeric|min:0.01',
            'items.*.precio' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $caja = Caja::where('id_caja', $request->id_caja)->where('estado', 1)->first();

            if (!$caja) {
                throw new \Exception('La caja seleccionada no está abierta');
            }

            $subtotal = 0;

            foreach ($request->items as $item) {
                $subtotal += (float) $item['precio'] * (float) $item['cantidad'];
            }

            $num = generarNumeroDocumento('VTA', 'ventas', $caja->id_tienda);

            $venta = Venta::create([
                'numero' => $num['numero'],
                'correlativo' => $num['correlativo'],
                'id_caja' => $caja->id_caja,
                'id_tienda' => $caja->id_tienda,
                'id_usuario' => auth()->id(),
                'id_cliente' => $request->id_cliente ?: null,
                'nombre_cliente' => $request->nombre_cliente ?: 'CLIENTES VARIOS',
                'id_metodo_pago' => $request->id_metodo_pago,
                'id_vendedor' => $caja->id_vendedor,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'estado' => 1,
            ]);

            foreach ($request->items as $item) {
                $cantidad = (int) $item['cantidad'];
                $precio = (float) $item['precio'];

                VentaDetalle::create([
                    'id_venta' => $venta->id_venta,
                    'id_variante' => $item['id_variante'],
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                    'subtotal' => $precio * $cantidad,
                ]);

                $this->inventario->aplicar(
                    $item['id_variante'],
                    $caja->id_tienda,
                    'venta',
                    $cantidad,
                    $venta->id_venta,
                    auth()->id(),
                    'Venta ' . $venta->numero
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'numero' => $venta->numero,
                'cliente' => $venta->nombre_cliente,
                'items' => count($request->items),
                'total' => $venta->total,
                'fecha' => $venta->created_at->format('d/m/Y H:i'),
                'tienda' => $caja->tienda ? $caja->tienda->nombre : '',
                'caja' => $caja->nombre,
                'vendedor' => $caja->vendedor ? $caja->vendedor->nombre : '',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function historial(Request $request)
    {
        $query = Venta::with(['tienda', 'caja', 'usuario', 'cliente', 'vendedor'])
            ->orderBy('id_venta', 'desc');

        if ($request->get('id_tienda')) {
            $query->where('id_tienda', $request->get('id_tienda'));
        }

        if ($request->get('id_vendedor')) {
            $query->where('id_vendedor', $request->get('id_vendedor'));
        }

        if ($request->get('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->get('fecha_desde'));
        }

        if ($request->get('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->get('fecha_hasta'));
        }

        $ventas = $query->paginate(30);
        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $vendedores = Vendedor::where('estado', 1)->orderBy('nombre', 'asc')->get();

        return view('admin.ventas.historial', compact('ventas', 'tiendas', 'vendedores'));
    }

    public function anular($id)
    {
        try {
            DB::beginTransaction();

            $venta = Venta::with('detalle')->findOrFail($id);

            if ($venta->estado == 0) {
                throw new \Exception('Esta venta ya está anulada');
            }

            foreach ($venta->detalle as $detalle) {
                $this->inventario->aplicar(
                    $detalle->id_variante,
                    $venta->id_tienda,
                    'ajuste',
                    $detalle->cantidad,
                    $venta->id_venta,
                    auth()->id(),
                    'Anulación de venta ' . $venta->numero
                );
            }

            $venta->update(['estado' => 0]);

            DB::commit();

            return redirect()->route('admin.ventas.historial')
                ->with('success', 'Venta anulada: stock restablecido');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function detalle($id)
    {
        $venta = Venta::with(['tienda', 'caja', 'usuario', 'cliente', 'vendedor', 'metodoPago', 'detalle.variante.producto'])
            ->findOrFail($id);

        return response()->json([
            'venta' => $venta,
        ]);
    }
}
