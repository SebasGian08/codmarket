<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Tienda;
use App\Models\Transferencia;
use App\Models\TransferenciaDetalle;
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

    public function index()
    {
        $transferencias = Transferencia::with(['tiendaOrigen', 'tiendaDestino', 'usuario'])
            ->orderBy('id_transferencia', 'desc')
            ->get();

        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();

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

        return view('admin.transferencias.index', compact('transferencias', 'tiendas', 'variantes', 'stockPorTienda'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tienda_origen' => 'required|exists:tiendas,id_tienda',
            'id_tienda_destino' => 'required|exists:tiendas,id_tienda|different:id_tienda_origen',
            'fecha' => 'required|date',
            'observacion' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.id_variante' => 'required|exists:productos_variantes,id_variante',
            'items.*.cantidad' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            $num = generarNumeroDocumento('TRF', 'transferencias', $request->id_tienda_origen, 'id_tienda_origen');

            $transferencia = Transferencia::create([
                'numero' => $num['numero'],
                'correlativo' => $num['correlativo'],
                'id_tienda_origen' => $request->id_tienda_origen,
                'id_tienda_destino' => $request->id_tienda_destino,
                'id_usuario' => auth()->id(),
                'fecha' => $request->fecha,
                'observacion' => $request->observacion,
                'estado' => 'pendiente',
            ]);

            foreach ($request->items as $item) {
                TransferenciaDetalle::create([
                    'id_transferencia' => $transferencia->id_transferencia,
                    'id_variante' => $item['id_variante'],
                    'cantidad' => (int) $item['cantidad'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.transferencias.index')
                ->with('success', 'Transferencia ' . $transferencia->numero . ' creada correctamente');
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
