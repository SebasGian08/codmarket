<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingreso;
use App\Models\IngresoDetalle;
use App\Models\Proveedor;
use App\Models\Tienda;
use App\Models\Venta;
use App\Services\InventarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngresoController extends Controller
{
    protected $inventario;

    public function __construct(InventarioService $inventario)
    {
        $this->inventario = $inventario;
    }

    public function index(Request $request)
    {
        $filtros = [
            'numero' => $request->query('numero'),
            'tipo' => $request->query('tipo'),
            'id_tienda' => $request->query('id_tienda'),
            'id_proveedor' => $request->query('id_proveedor'),
            'estado' => $request->query('estado'),
            'fecha_desde' => $request->query('fecha_desde'),
            'fecha_hasta' => $request->query('fecha_hasta'),
        ];

        $query = Ingreso::with(['tienda', 'proveedor', 'venta', 'usuario']);

        if (!empty($filtros['numero'])) {
            $query->where('numero', 'like', '%' . $filtros['numero'] . '%');
        }

        if (!empty($filtros['tipo'])) {
            $query->where('tipo', $filtros['tipo']);
        }

        if (!empty($filtros['id_tienda'])) {
            $query->where('id_tienda', $filtros['id_tienda']);
        }

        if (!empty($filtros['id_proveedor'])) {
            $query->where('id_proveedor', $filtros['id_proveedor']);
        }

        if ($filtros['estado'] !== null && $filtros['estado'] !== '') {
            $query->where('estado', (int) $filtros['estado']);
        }

        if (!empty($filtros['fecha_desde'])) {
            $query->whereDate('fecha', '>=', $filtros['fecha_desde']);
        }

        if (!empty($filtros['fecha_hasta'])) {
            $query->whereDate('fecha', '<=', $filtros['fecha_hasta']);
        }

        $ingresos = $query->orderBy('id_ingreso', 'desc')->get();

        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $proveedores = Proveedor::where('estado', 1)->orderBy('nombre', 'asc')->get();

        $variantes = \App\Models\ProductoVariante::with(['producto', 'atributos.atributo'])
            ->where('estado', 1)
            ->get()
            ->map(function ($v) {
                $atributos = $v->atributos->map(function ($a) {
                    return [
                        'atributo' => $a->atributo->nombre ?? '',
                        'valor' => $a->valor,
                    ];
                })->values();

                return [
                    'id' => $v->id_variante,
                    'sku' => $v->sku,
                    'producto' => $v->producto->nombre ?? '',
                    'atributos' => $atributos,
                    'costo' => (float) $v->costo,
                ];
            })
            ->values();

        return view('admin.ingresos.index', compact('ingresos', 'tiendas', 'proveedores', 'variantes', 'filtros'));
    }

    public function buscarVentasParaDevolucion(Request $request)
    {
        $termino = trim((string) $request->query('q', ''));

        if (mb_strlen($termino) < 2) {
            return response()->json([]);
        }

        $ventas = Venta::with('cliente')
            ->where('estado', 1)
            ->where(function ($query) use ($termino) {
                $query->where('numero', 'like', '%' . $termino . '%')
                    ->orWhere('nombre_cliente', 'like', '%' . $termino . '%')
                    ->orWhereHas('cliente', function ($clienteQuery) use ($termino) {
                        $clienteQuery->where('nombre', 'like', '%' . $termino . '%');
                    });
            })
            ->orderByDesc('id_venta')
            ->limit(15)
            ->get(['id_venta', 'numero', 'id_tienda', 'id_cliente', 'nombre_cliente', 'created_at'])
            ->map(function ($venta) {
                return [
                    'id' => $venta->id_venta,
                    'id_tienda' => $venta->id_tienda,
                    'numero' => $venta->numero,
                    'cliente' => $venta->nombre_cliente ?: ($venta->cliente->nombre ?? 'Cliente no identificado'),
                    'fecha' => optional($venta->created_at)->format('d/m/Y'),
                ];
            });

        return response()->json($ventas);
    }

    public function variantesParaDevolucion($id)
    {
        $venta = Venta::with([
            'detalle.variante.producto',
            'detalle.variante.atributos.atributo',
        ])->where('estado', 1)->findOrFail($id);

        $devueltoPorVariante = IngresoDetalle::query()
            ->join('ingresos', 'ingresos.id_ingreso', '=', 'ingresos_detalle.id_ingreso')
            ->where('ingresos.id_venta', $venta->id_venta)
            ->where('ingresos.tipo', 'devolucion')
            ->where('ingresos.estado', 1)
            ->selectRaw('ingresos_detalle.id_variante, SUM(ingresos_detalle.cantidad) as cantidad')
            ->groupBy('ingresos_detalle.id_variante')
            ->pluck('cantidad', 'id_variante');

        $variantes = $venta->detalle
            ->groupBy('id_variante')
            ->map(function ($detalles, $idVariante) use ($devueltoPorVariante) {
                $variante = $detalles->first()->variante;
                $vendido = (int) $detalles->sum('cantidad');
                $devuelto = (int) ($devueltoPorVariante[$idVariante] ?? 0);

                return [
                    'id' => (int) $idVariante,
                    'sku' => $variante->sku,
                    'producto' => $variante->producto->nombre ?? '',
                    'atributos' => $variante->atributos->map(function ($atributoValor) {
                        return [
                            'atributo' => $atributoValor->atributo->nombre ?? '',
                            'valor' => $atributoValor->valor,
                        ];
                    })->values(),
                    'costo' => (float) $variante->costo,
                    'cantidad_disponible' => max(0, $vendido - $devuelto),
                ];
            })
            ->filter(function ($variante) {
                return $variante['cantidad_disponible'] > 0;
            })
            ->values();

        return response()->json([
            'id_venta' => $venta->id_venta,
            'id_tienda' => $venta->id_tienda,
            'variantes' => $variantes,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:compra,ajuste_suma,ajuste_resta,devolucion',
            'id_proveedor' => 'required_if:tipo,compra|nullable|exists:proveedores,id_proveedor',
            'id_tienda' => 'required|exists:tiendas,id_tienda',
            'id_venta' => 'required_if:tipo,devolucion|nullable|exists:ventas,id_venta',
            'fecha' => 'required|date',
            'observacion' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.id_variante' => 'required|exists:productos_variantes,id_variante',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.costo' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $venta = null;
            $idTienda = (int) $request->id_tienda;

            if ($request->tipo === 'devolucion') {
                $venta = Venta::where('estado', 1)
                    ->lockForUpdate()
                    ->findOrFail($request->id_venta);
                $idTienda = $venta->id_tienda;

                $vendidoPorVariante = $venta->detalle()
                    ->selectRaw('id_variante, SUM(cantidad) as cantidad')
                    ->groupBy('id_variante')
                    ->pluck('cantidad', 'id_variante');

                $devueltoPorVariante = IngresoDetalle::query()
                    ->join('ingresos', 'ingresos.id_ingreso', '=', 'ingresos_detalle.id_ingreso')
                    ->where('ingresos.id_venta', $venta->id_venta)
                    ->where('ingresos.tipo', 'devolucion')
                    ->where('ingresos.estado', 1)
                    ->selectRaw('ingresos_detalle.id_variante, SUM(ingresos_detalle.cantidad) as cantidad')
                    ->groupBy('ingresos_detalle.id_variante')
                    ->pluck('cantidad', 'id_variante');

                $solicitadoPorVariante = [];
                foreach ($request->items as $item) {
                    $idVariante = (int) $item['id_variante'];
                    $solicitadoPorVariante[$idVariante] = ($solicitadoPorVariante[$idVariante] ?? 0)
                        + (int) $item['cantidad'];
                }

                foreach ($solicitadoPorVariante as $idVariante => $cantidad) {
                    $cantidadVendida = (int) ($vendidoPorVariante[$idVariante] ?? 0);
                    $cantidadDevuelta = (int) ($devueltoPorVariante[$idVariante] ?? 0);

                    if ($cantidadVendida === 0 || $cantidad > $cantidadVendida - $cantidadDevuelta) {
                        throw new \Exception('La cantidad devuelta supera las unidades disponibles de la venta seleccionada.');
                    }
                }
            }

            $num = generarNumeroDocumento('ING', 'ingresos', $idTienda);

            $ingreso = Ingreso::create([
                'numero' => $num['numero'],
                'correlativo' => $num['correlativo'],
                'tipo' => $request->tipo,
                'id_proveedor' => $request->tipo === 'compra' ? $request->id_proveedor : null,
                'id_venta' => $venta ? $venta->id_venta : null,
                'id_tienda' => $idTienda,
                'id_usuario' => auth()->id(),
                'fecha' => $request->fecha,
                'observacion' => $request->observacion,
                'estado' => 1,
            ]);

            foreach ($request->items as $item) {
                $cantidad = (int) $item['cantidad'];
                $costo = (float) ($item['costo'] ?? 0);

                IngresoDetalle::create([
                    'id_ingreso' => $ingreso->id_ingreso,
                    'id_variante' => $item['id_variante'],
                    'cantidad' => $cantidad,
                    'costo' => $costo,
                ]);

                $tipoMovimiento = [
                    'compra' => 'ingreso',
                    'ajuste_suma' => 'ajuste_suma',
                    'ajuste_resta' => 'ajuste_resta',
                    'devolucion' => 'devolucion',
                ][$request->tipo];
                $observacion = $request->tipo === 'devolucion'
                    ? 'Devolución de la venta ' . $venta->numero
                    : 'Ingreso ' . $ingreso->numero;

                $this->inventario->aplicar(
                    $item['id_variante'],
                    $idTienda,
                    $tipoMovimiento,
                    $cantidad,
                    $ingreso->id_ingreso,
                    auth()->id(),
                    $observacion
                );
            }

            DB::commit();

            return redirect()->route('admin.ingresos.index')
                ->with('success', 'Ingreso ' . $ingreso->numero . ' registrado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function anular($id)
    {
        try {
            DB::beginTransaction();

            $ingreso = Ingreso::findOrFail($id);

            if ($ingreso->estado == 0) {
                throw new \Exception('Este ingreso ya está anulado');
            }

            foreach ($ingreso->detalle as $detalle) {
                $tipoReversion = [
                    'compra' => 'ajuste_resta',
                    'ajuste' => 'ajuste',
                    'ajuste_suma' => 'ajuste_resta',
                    'ajuste_resta' => 'ajuste_suma',
                    'devolucion' => 'ajuste_resta',
                ][$ingreso->tipo] ?? 'ajuste';

                $this->inventario->aplicar(
                    $detalle->id_variante,
                    $ingreso->id_tienda,
                    $tipoReversion,
                    $ingreso->tipo === 'ajuste' ? -$detalle->cantidad : $detalle->cantidad,
                    $ingreso->id_ingreso,
                    auth()->id(),
                    'Anulación de ingreso ' . $ingreso->numero
                );
            }

            $ingreso->update(['estado' => 0]);

            DB::commit();

            return redirect()->route('admin.ingresos.index')
                ->with('success', 'Ingreso anulado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function detalle($id)
    {
        $ingreso = Ingreso::with(['tienda', 'proveedor', 'venta', 'usuario', 'detalle.variante.producto'])
            ->findOrFail($id);

        return view('admin.ingresos.modals.detalle', compact('ingreso'));
    }
}
