<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\CuentaBancaria;
use App\Models\Inventario;
use App\Models\MetodoPago;
use App\Models\Producto;
use App\Models\Tienda;
use App\Models\Vendedor;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\VentaPago;
use App\Services\InventarioService;
use App\Services\MovimientoDineroService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    protected $inventario;
    protected $movimiento;

    public function __construct(InventarioService $inventario, MovimientoDineroService $movimiento)
    {
        $this->inventario = $inventario;
        $this->movimiento = $movimiento;
    }

    public function index()
    {
        $productos = Producto::with(['variantes.atributos.atributo', 'variantes.imagenes', 'imagenes'])
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
                            $img = $v->imagenes->where('principal', 1)->first() ?? $v->imagenes->first();
                            return [
                                'id' => $v->id_variante,
                                'sku' => $v->sku,
                                'precio' => (float) $v->precio,
                                'precio_oferta' => $v->precio_oferta !== null ? (float) $v->precio_oferta : null,
                                'stock' => (int) $v->stock,
                                'imagen' => $img
                                    ? asset($img->url)
                                    : asset('assets/images/tienda_virtual/default.png'),
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
            'id_metodo_pago' => 'nullable|exists:metodos_pagos,id_metodo_pago',
            'monto_recibido' => 'nullable|numeric|min:0',
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
                'id_metodo_pago' => $request->id_metodo_pago ?: null,
                'id_vendedor' => $caja->id_vendedor,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'monto_recibido' => $request->monto_recibido ?: null,
                'estado' => 1,
                'estado_cobro' => 'pendiente',
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
        $query = Venta::with(['tienda', 'caja', 'usuario', 'cliente', 'vendedor', 'ventaPagos.metodoPago'])
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

            $venta = Venta::with(['detalle', 'ventaPagos'])->findOrFail($id);

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

            // Si la venta ya fue cobrada (cerrada), revertir los movimientos de dinero
            if ($venta->estado_cobro === 'cerrado' && $venta->ventaPagos->isNotEmpty()) {
                $this->movimiento->revertirPorReferencia(
                    MovimientoDineroService::TIPO_VENTA,
                    MovimientoDineroService::REF_VENTA_PAGO,
                    $venta->id_venta,
                    'Anulación de venta ' . $venta->numero
                );
            }

            $venta->update([
                'estado' => 0,
                'estado_cobro' => 'cerrado',
            ]);

            DB::commit();

            return redirect()->route('admin.ventas.historial')
                ->with('success', 'Venta anulada: stock y dinero revertidos');
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

    /* ================================================================
       CERRAR VENTA
       ================================================================ */

    public function cerrarVenta()
    {
        $ventasPendientes = Venta::with(['tienda', 'usuario', 'cliente', 'vendedor', 'detalle'])
            ->where('estado', 1)
            ->where('estado_cobro', 'pendiente')
            ->orderBy('id_venta', 'desc')
            ->get()
            ->map(function ($v) {
                $v->total_items = $v->detalle->sum('cantidad');
                return $v;
            });

        return view('admin.ventas.cerrar_venta', compact('ventasPendientes'));
    }

    public function obtenerCierre($id)
    {
        $venta = Venta::with([
            'tienda', 'usuario', 'cliente', 'vendedor',
            'detalle.variante.producto',
            'ventaPagos.metodoPago',
            'ventaPagos.cuentaBancaria'
        ])->findOrFail($id);

        if ($venta->estado_cobro !== 'pendiente') {
            return response()->json(['message' => 'Esta venta ya fue cerrada'], 422);
        }

        $metodosPagos = MetodoPago::where('estado', 1)->orderBy('nombre')->get();
        $cuentas = CuentaBancaria::where('estado', 1)->orderBy('nombre_banco')->get();

        return response()->json([
            'venta' => $venta,
            'metodosPagos' => $metodosPagos,
            'cuentas' => $cuentas,
        ]);
    }

    public function actualizarDetalle(Request $request, $id)
    {
        $venta = Venta::with('detalle')->findOrFail($id);

        if ($venta->estado_cobro !== 'pendiente') {
            return response()->json(['message' => 'Esta venta ya fue cerrada'], 422);
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id_variante' => 'required|exists:productos_variantes,id_variante',
            'items.*.cantidad' => 'required|numeric|min:0.01',
            'items.*.precio' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Stock difference tracking
            $stockOriginal = [];
            foreach ($venta->detalle as $d) {
                $key = $d->id_variante;
                $stockOriginal[$key] = ($stockOriginal[$key] ?? 0) + $d->cantidad;
            }

            $stockNuevo = [];
            foreach ($request->items as $item) {
                $key = $item['id_variante'];
                $stockNuevo[$key] = ($stockNuevo[$key] ?? 0) + (int) $item['cantidad'];
            }

            // Revert stock for removed/changed items
            foreach ($stockOriginal as $varId => $cantOriginal) {
                $cantNueva = $stockNuevo[$varId] ?? 0;
                $diferencia = $cantOriginal - $cantNueva;

                if ($diferencia > 0) {
                    $this->inventario->aplicar(
                        $varId, $venta->id_tienda, 'ajuste', $diferencia,
                        $venta->id_venta, auth()->id(),
                        'Ajuste cierre venta ' . $venta->numero
                    );
                }
            }

            // Apply stock for new/increased items
            foreach ($stockNuevo as $varId => $cantNueva) {
                $cantOriginal = $stockOriginal[$varId] ?? 0;
                $diferencia = $cantNueva - $cantOriginal;

                if ($diferencia > 0) {
                    $this->inventario->aplicar(
                        $varId, $venta->id_tienda, 'venta', $diferencia,
                        $venta->id_venta, auth()->id(),
                        'Ajuste cierre venta ' . $venta->numero
                    );
                }
            }

            // Delete old detail
            VentaDetalle::where('id_venta', $id)->delete();

            // Insert new detail
            $subtotal = 0;
            foreach ($request->items as $item) {
                $cantidad = (int) $item['cantidad'];
                $precio = (float) $item['precio'];
                $sub = $precio * $cantidad;
                $subtotal += $sub;

                VentaDetalle::create([
                    'id_venta' => $venta->id_venta,
                    'id_variante' => $item['id_variante'],
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                    'subtotal' => $sub,
                ]);
            }

            $venta->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'total' => $subtotal]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function agregarPago(Request $request, $id)
    {
        $venta = Venta::findOrFail($id);

        if ($venta->estado_cobro !== 'pendiente') {
            return response()->json(['message' => 'Esta venta ya fue cerrada'], 422);
        }

        $request->validate([
            'id_metodo_pago' => 'required|exists:metodos_pagos,id_metodo_pago',
            'id_cuenta_bancaria' => 'required|exists:cuentas_bancarias,id_cuenta_bancaria',
            'monto' => 'required|numeric|min:0.01',
        ]);

        $pago = VentaPago::create([
            'id_venta' => $venta->id_venta,
            'id_metodo_pago' => $request->id_metodo_pago,
            'id_cuenta_bancaria' => $request->id_cuenta_bancaria,
            'monto' => (float) $request->monto,
            'moneda' => 'PEN',
            'id_usuario_registro' => auth()->id(),
        ]);

        $pago->load('metodoPago', 'cuentaBancaria');

        $totalPagado = $venta->ventaPagos()->sum('monto');

        return response()->json([
            'success' => true,
            'pago' => $pago,
            'totalPagado' => $totalPagado,
        ]);
    }

    public function eliminarPago($id, $idPago)
    {
        $venta = Venta::findOrFail($id);

        if ($venta->estado_cobro !== 'pendiente') {
            return response()->json(['message' => 'Esta venta ya fue cerrada'], 422);
        }

        VentaPago::where('id_venta_pago', $idPago)
            ->where('id_venta', $id)
            ->delete();

        $totalPagado = $venta->ventaPagos()->sum('monto');

        return response()->json([
            'success' => true,
            'totalPagado' => $totalPagado,
        ]);
    }

    public function procesarCierre(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $venta = Venta::with('ventaPagos')->findOrFail($id);

            if ($venta->estado_cobro !== 'pendiente') {
                throw new \Exception('Esta venta ya fue cerrada');
            }

            if ($venta->estado == 0) {
                throw new \Exception('No se puede cerrar una venta anulada');
            }

            // Pagos enviados desde el modal (persistir antes de cerrar)
            $pagos = $request->input('pagos', []);

            // Suma de pagos antes de ingresar para validar contra el total
            $sumaPagos = array_reduce($pagos, function ($carry, $p) {
                return $carry + (float) ($p['monto'] ?? 0);
            }, 0.0);

            $totalVenta = (float) $venta->total;

            if (empty($pagos)) {
                throw new \Exception('Debe registrar al menos un pago');
            }

            if (abs($sumaPagos - $totalVenta) > 0.01) {
                throw new \Exception(
                    'La suma de los pagos (S/ ' . number_format($sumaPagos, 2) .
                    ') no coincide con el total de la venta (S/ ' . number_format($totalVenta, 2) . ')'
                );
            }

            // Reemplazar los pagos locales del modal
            VentaPago::where('id_venta', $venta->id_venta)->delete();

            // Limpiar movimientos financieros previos (reintento de cierre)
            DB::table('movimientos_dinero')
                ->where('referencia_tipo', MovimientoDineroService::REF_VENTA_PAGO)
                ->where('id_referencia', $venta->id_venta)
                ->delete();

            foreach ($pagos as $pago) {
                $idMetodo = $pago['id_metodo_pago'] ?? null;
                $idCuenta = $pago['id_cuenta_bancaria'] ?? null;
                $monto = (float) ($pago['monto'] ?? 0);

                // Validar método de pago y su destino configurado (maestro destinos_pago)
                $metodo = MetodoPago::with('destinoPago')->find($idMetodo);
                if (!$metodo) {
                    throw new \Exception('Debe seleccionar un método de pago válido');
                }
                if (!$metodo->destinoPago) {
                    throw new \Exception('El método ' . $metodo->nombre . ' no tiene un destino financiero configurado');
                }

                // El origen/destino se resuelve desde el maestro: caja o cuenta.
                if ($metodo->afectaCuenta()) {
                    $cuentaExiste = DB::table('cuentas_bancarias')
                        ->where('id_cuenta_bancaria', $idCuenta)->where('estado', 1)
                        ->exists();
                    if (!$cuentaExiste) {
                        throw new \Exception(
                            'El método ' . $metodo->nombre .
                            ' requiere seleccionar una cuenta bancaria'
                        );
                    }
                } else {
                    // Método de caja (ej. efectivo): no usa cuenta bancaria
                    $idCuenta = null;
                }

                if ($monto <= 0) {
                    throw new \Exception('El monto de cada pago debe ser mayor a 0');
                }

                VentaPago::create([
                    'id_venta' => $venta->id_venta,
                    'id_metodo_pago' => $metodo->id_metodo_pago,
                    'id_cuenta_bancaria' => $idCuenta,
                    'monto' => $monto,
                    'moneda' => 'PEN',
                    'id_usuario_registro' => auth()->id(),
                ]);

                // Emitir el movimiento financiero correspondiente (caja o cuenta)
                $this->movimiento->aplicarVentaPago(
                    $venta->id_venta,
                    $venta->id_caja,
                    [
                        'id_metodo_pago' => $metodo->id_metodo_pago,
                        'id_cuenta_bancaria' => $idCuenta,
                        'monto' => $monto,
                        'moneda' => 'PEN',
                    ]
                );
            }

            $venta->load('ventaPagos');

            if ($venta->ventaPagos->isEmpty()) {
                throw new \Exception('Debe registrar al menos un pago');
            }

            // Update first payment method on the sale for backward compatibility
            $primerPago = $venta->ventaPagos->first();
            $venta->update([
                'estado_cobro' => 'cerrado',
                'fecha_cierre' => now(),
                'usuario_cierre' => auth()->id(),
                'id_metodo_pago' => $primerPago->id_metodo_pago,
                'monto_recibido' => $venta->ventaPagos->sum('monto'),
            ]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
