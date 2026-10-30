<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Tienda;
use App\Models\Transferencia;
use App\Models\TransferenciaDetalle;
use App\Models\Vendedor;
use App\Services\InventarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferenciaController extends Controller
{
    protected $inventario;

    public function __construct(InventarioService $inventario)
    {
        $this->inventario = $inventario;
    }

    protected function tiendasAsignadasUsuario()
    {
        $vendedor = Vendedor::where('id_usuario', auth()->id())
            ->where('estado', 1)
            ->first();

        if (!$vendedor) {
            return collect();
        }

        return $vendedor->tiendas()
            ->where('tiendas.estado', 1)
            ->wherePivot('estado', 1)
            ->orderBy('tiendas.nombre')
            ->get();
    }

    protected function usuarioTieneTiendaAsignada($idTienda)
    {
        return $this->tiendasAsignadasUsuario()->contains(function ($tienda) use ($idTienda) {
            return (int) $tienda->id_tienda === (int) $idTienda;
        });
    }

    protected function validarUsuarioNoRegistrador(Transferencia $transferencia)
    {
        if ((int) $transferencia->id_usuario === (int) auth()->id()) {
            throw new \Exception('La persona que registró la transferencia solo puede consultarla');
        }
    }

    protected function guardarDetalleTransferencia(Transferencia $transferencia)
    {
        foreach ($transferencia->detalle as $detalle) {
            $stockOrigen = Inventario::where('id_variante', $detalle->id_variante)
                ->where('id_tienda', $transferencia->id_tienda_origen)
                ->value('cantidad') ?? 0;

            if ($stockOrigen < $detalle->cantidad) {
                $variante = $detalle->variante;
                throw new \Exception('Stock insuficiente en origen para ' . ($variante->sku ?? 'variante #' . $detalle->id_variante));
            }

            $this->inventario->aplicar(
                $detalle->id_variante,
                $transferencia->id_tienda_origen,
                'transferencia_salida',
                $detalle->cantidad,
                $transferencia->id_transferencia,
                auth()->id(),
                'Transferencia ' . $transferencia->numero
            );
        }

        $transferencia->update(['estado' => 'en_transito']);
    }

    public function index(Request $request)
    {
        $filtros = [
            'numero' => $request->query('numero'),
            'id_tienda_origen' => $request->query('id_tienda_origen'),
            'id_tienda_destino' => $request->query('id_tienda_destino'),
            'estado' => $request->query('estado'),
            'fecha_desde' => $request->query('fecha_desde'),
            'fecha_hasta' => $request->query('fecha_hasta'),
        ];

        $query = Transferencia::with(['tiendaOrigen', 'tiendaDestino', 'usuario']);

        if (!empty($filtros['numero'])) {
            $query->where(function ($q) use ($filtros) {
                $q->where('numero', 'like', '%' . $filtros['numero'] . '%')
                    ->orWhere('correlativo', 'like', '%' . $filtros['numero'] . '%');
            });
        }

        if (!empty($filtros['id_tienda_origen'])) {
            $query->where('id_tienda_origen', $filtros['id_tienda_origen']);
        }

        if (!empty($filtros['id_tienda_destino'])) {
            $query->where('id_tienda_destino', $filtros['id_tienda_destino']);
        }

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['fecha_desde'])) {
            $query->whereDate('fecha', '>=', $filtros['fecha_desde']);
        }

        if (!empty($filtros['fecha_hasta'])) {
            $query->whereDate('fecha', '<=', $filtros['fecha_hasta']);
        }

        $transferencias = $query->orderBy('id_transferencia', 'desc')->get();

        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();

        $tiendasAsignadas = $this->tiendasAsignadasUsuario();
        $idsTiendasAsignadas = $tiendasAsignadas->pluck('id_tienda')->map(function ($id) {
            return (int) $id;
        })->all();
        $tiendasOrigen = $tiendasAsignadas;

        $variantes = \App\Models\ProductoVariante::with(['producto', 'atributos.atributo'])
            ->where('estado', 1)
            ->get()
            ->map(function ($v) {
                return [
                    'id' => $v->id_variante,
                    'sku' => $v->sku,
                    'producto' => $v->producto->nombre ?? '',
                    'atributos' => $v->atributos
                        ->map(function ($av) {
                            return [
                                'atributo' => $av->atributo->nombre ?? 'Atributo',
                                'valor' => $av->valor,
                            ];
                        })
                        ->values(),
                ];
            })
            ->values();

        $stockPorTienda = Inventario::get()
            ->groupBy('id_variante')
            ->map(function ($grupo) {
                return $grupo->pluck('cantidad', 'id_tienda')->toArray();
            })
            ->toArray();

        return view('admin.transferencias.index', compact('transferencias', 'tiendas', 'variantes', 'stockPorTienda', 'tiendasAsignadas', 'tiendasOrigen', 'idsTiendasAsignadas', 'filtros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tienda_origen' => 'required|exists:tiendas,id_tienda',
            'id_tienda_destino' => 'required|exists:tiendas,id_tienda,estado,1|different:id_tienda_origen',
            'fecha' => 'required|date',
            'observacion' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.id_variante' => 'required|exists:productos_variantes,id_variante',
            'items.*.cantidad' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            $tiendasAsignadas = $this->tiendasAsignadasUsuario();
            $idsTiendasAsignadas = $tiendasAsignadas->pluck('id_tienda')->map(function ($id) {
                return (int) $id;
            })->all();

            if (!in_array((int) $request->id_tienda_origen, $idsTiendasAsignadas, true)) {
                throw new \Exception('La tienda origen no está asignada a tu usuario');
            }

            $idTiendaOrigen = (int) $request->id_tienda_origen;
            $idTiendaDestino = $request->id_tienda_destino;

            if ((int) $idTiendaOrigen === (int) $idTiendaDestino) {
                throw new \Exception('La tienda origen y destino deben ser diferentes');
            }

            $num = generarNumeroDocumento('TRF', 'transferencias', $idTiendaOrigen, 'id_tienda_origen');

            $transferencia = Transferencia::create([
                'numero' => $num['numero'],
                'correlativo' => $num['correlativo'],
                'id_tienda_origen' => $idTiendaOrigen,
                'id_tienda_destino' => $idTiendaDestino,
                'id_usuario' => auth()->id(),
                'fecha' => $request->fecha,
                'observacion' => $request->observacion,
                'estado' => 'en_transito',
            ]);

            foreach ($request->items as $item) {
                TransferenciaDetalle::create([
                    'id_transferencia' => $transferencia->id_transferencia,
                    'id_variante' => $item['id_variante'],
                    'cantidad' => (int) $item['cantidad'],
                ]);
            }

            // Al registrarse se descuenta el stock del origen y queda en tránsito,
            // sin necesidad de un paso extra de "enviar".
            $transferencia->load('detalle');
            $this->guardarDetalleTransferencia($transferencia);

            DB::commit();

            return redirect()->route('admin.transferencias.index')
                ->with('success', 'Transferencia ' . $transferencia->numero . ' creada: stock descontado de la tienda origen, a la espera de aprobación en destino');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function enviar($id)
    {
        try {
            DB::beginTransaction();

            $transferencia = Transferencia::with('detalle')->findOrFail($id);

            if ($transferencia->estado !== 'pendiente') {
                throw new \Exception('Solo se puede enviar una transferencia pendiente');
            }

            $this->validarUsuarioNoRegistrador($transferencia);

            if (!$this->usuarioTieneTiendaAsignada($transferencia->id_tienda_origen)) {
                throw new \Exception('No tienes asignada la tienda origen de esta transferencia');
            }

            $this->guardarDetalleTransferencia($transferencia);

            DB::commit();

            return redirect()->route('admin.transferencias.index')
                ->with('success', 'Transferencia enviada: stock descontado de la tienda origen');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function recibir($id)
    {
        try {
            DB::beginTransaction();

            $transferencia = Transferencia::with('detalle')->findOrFail($id);

            if ($transferencia->estado !== 'en_transito') {
                throw new \Exception('Solo se puede recibir una transferencia en tránsito');
            }

            $this->validarUsuarioNoRegistrador($transferencia);

            if (!$this->usuarioTieneTiendaAsignada($transferencia->id_tienda_destino)) {
                throw new \Exception('No tienes asignada la tienda destino de esta transferencia');
            }

            foreach ($transferencia->detalle as $detalle) {
                $this->inventario->aplicar(
                    $detalle->id_variante,
                    $transferencia->id_tienda_destino,
                    'transferencia_entrada',
                    $detalle->cantidad,
                    $transferencia->id_transferencia,
                    auth()->id(),
                    'Transferencia ' . $transferencia->numero
                );
            }

            $transferencia->update(['estado' => 'recibida']);

            DB::commit();

            return redirect()->route('admin.transferencias.index')
                ->with('success', 'Transferencia recibida: stock ingresado en la tienda destino');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function anular($id)
    {
        try {
            DB::beginTransaction();

            $transferencia = Transferencia::with('detalle')->findOrFail($id);

            if ($transferencia->estado === 'recibida') {
                throw new \Exception('No se puede anular una transferencia recibida');
            }

            $this->validarUsuarioNoRegistrador($transferencia);

            if (!$this->usuarioTieneTiendaAsignada($transferencia->id_tienda_origen)) {
                throw new \Exception('No tienes asignada la tienda origen de esta transferencia');
            }

            // Si estaba en tránsito, se devuelve el stock a la tienda origen
            if ($transferencia->estado === 'en_transito') {
                foreach ($transferencia->detalle as $detalle) {
                    $this->inventario->aplicar(
                        $detalle->id_variante,
                        $transferencia->id_tienda_origen,
                        'ajuste',
                        $detalle->cantidad,
                        $transferencia->id_transferencia,
                        auth()->id(),
                        'Anulación de transferencia ' . $transferencia->numero
                    );
                }
            }

            $transferencia->update(['estado' => 'anulada']);

            DB::commit();

            return redirect()->route('admin.transferencias.index')
                ->with('success', 'Transferencia anulada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function detalle($id)
    {
        $transferencia = Transferencia::with(['tiendaOrigen', 'tiendaDestino', 'usuario', 'detalle.variante.producto', 'detalle.variante.atributos.atributo'])
            ->findOrFail($id);

        return view('admin.transferencias.modals.detalle', compact('transferencia'));
    }
}
