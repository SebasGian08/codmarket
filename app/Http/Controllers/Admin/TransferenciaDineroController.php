<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\CuentaBancaria;
use App\Models\Tienda;
use App\Models\TransferenciaDinero;
use App\Services\MovimientoDineroService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferenciaDineroController extends Controller
{
    protected $movimiento;

    public function __construct(MovimientoDineroService $movimiento)
    {
        $this->movimiento = $movimiento;
    }

    public function index(Request $request)
    {
        $filtros = [
            'numero' => $request->query('numero'),
            'id_tienda' => $request->query('id_tienda'),
            'estado' => $request->query('estado'),
            'fecha_desde' => $request->query('fecha_desde'),
            'fecha_hasta' => $request->query('fecha_hasta'),
        ];

        $query = TransferenciaDinero::with([
            'tienda', 'cajaOrigen', 'cuentaOrigen', 'cajaDestino', 'cuentaDestino', 'usuario'
        ]);

        if (!empty($filtros['numero'])) {
            $query->where('numero', 'like', '%' . $filtros['numero'] . '%');
        }

        if (!empty($filtros['id_tienda'])) {
            $query->where('id_tienda', $filtros['id_tienda']);
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

        $transferencias = $query->orderBy('id_transferencia_dinero', 'desc')->get();

        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $cuentasBancarias = CuentaBancaria::where('estado', 1)->orderBy('nombre_banco', 'asc')->get();
        $cajasAbiertas = Caja::where('estado', 1)->with('tienda')->get();

        return view('admin.transferencias-dinero.index', compact(
            'transferencias', 'tiendas', 'cuentasBancarias', 'cajasAbiertas', 'filtros'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tienda' => 'required|exists:tiendas,id_tienda',
            'origen_tipo' => 'required|in:caja,cuenta',
            'id_caja_origen' => 'nullable|exists:cajas,id_caja',
            'id_cuenta_origen' => 'nullable|exists:cuentas_bancarias,id_cuenta_bancaria',
            'destino_tipo' => 'required|in:caja,cuenta',
            'id_caja_destino' => 'nullable|exists:cajas,id_caja',
            'id_cuenta_destino' => 'nullable|exists:cuentas_bancarias,id_cuenta_bancaria',
            'fecha' => 'required|date',
            'monto' => 'required|numeric|min:0.01',
            'observacion' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $idCajaOrigen = $request->origen_tipo === 'caja' ? $request->id_caja_origen : null;
            $idCuentaOrigen = $request->origen_tipo === 'cuenta' ? $request->id_cuenta_origen : null;
            $idCajaDestino = $request->destino_tipo === 'caja' ? $request->id_caja_destino : null;
            $idCuentaDestino = $request->destino_tipo === 'cuenta' ? $request->id_cuenta_destino : null;

            // Validar que ambos lados (origen y destino) estén definidos
            if (!$idCajaOrigen && !$idCuentaOrigen) {
                throw new \Exception('Debe indicar el origen del dinero');
            }
            if (!$idCajaDestino && !$idCuentaDestino) {
                throw new \Exception('Debe indicar el destino del dinero');
            }

            // Origen y destino no pueden ser el mismo lado financiero
            $mismoOrigen = [
                'caja' => $idCajaOrigen,
                'cuenta' => $idCuentaOrigen,
            ];
            $mismoDestino = [
                'caja' => $idCajaDestino,
                'cuenta' => $idCuentaDestino,
            ];
            if ($mismoOrigen['caja'] && $mismoDestino['caja'] && $mismoOrigen['caja'] == $mismoDestino['caja']) {
                throw new \Exception('El origen y el destino no pueden ser la misma caja');
            }
            if ($mismoOrigen['cuenta'] && $mismoDestino['cuenta'] && $mismoOrigen['cuenta'] == $mismoDestino['cuenta']) {
                throw new \Exception('El origen y el destino no pueden ser la misma cuenta');
            }

            $monto = (float) $request->monto;

            // Validar saldo suficiente en el origen
            if ($idCuentaOrigen) {
                $saldoOrigen = (float) DB::table('cuentas_bancarias')
                    ->where('id_cuenta_bancaria', $idCuentaOrigen)->value('saldo_actual');
                if ($saldoOrigen < $monto) {
                    throw new \Exception('Saldo insuficiente en la cuenta de origen (S/ ' . number_format($saldoOrigen, 2) . ')');
                }
            }
            if ($idCajaOrigen) {
                $saldoCajaOrigen = $this->movimiento->efectivoEsperadoCaja($idCajaOrigen);
                if ($saldoCajaOrigen < $monto) {
                    throw new \Exception('Efectivo insuficiente en la caja de origen (S/ ' . number_format($saldoCajaOrigen, 2) . ')');
                }
            }

            $num = generarNumeroDocumento('TRF', 'transferencias_dinero', $request->id_tienda);

            $transferencia = TransferenciaDinero::create([
                'numero' => $num['numero'],
                'correlativo' => $num['correlativo'],
                'id_tienda' => $request->id_tienda,
                'id_caja_origen' => $idCajaOrigen,
                'id_cuenta_origen' => $idCuentaOrigen,
                'id_caja_destino' => $idCajaDestino,
                'id_cuenta_destino' => $idCuentaDestino,
                'id_usuario' => auth()->id(),
                'fecha' => $request->fecha,
                'monto' => $monto,
                'moneda' => 'PEN',
                'observacion' => $request->observacion,
                'estado' => 1,
            ]);

            // Emitir salida (origen) + entrada (destino)
            $this->movimiento->aplicarTransferenciaDinero($transferencia);

            DB::commit();

            return redirect()->route('admin.transferencias-dinero.index')
                ->with('success', 'Transferencia de dinero ' . $transferencia->numero . ' realizada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function anular($id)
    {
        try {
            DB::beginTransaction();

            $transferencia = TransferenciaDinero::findOrFail($id);

            if ($transferencia->estado == 0) {
                throw new \Exception('Esta transferencia ya está anulada');
            }

            // Revertir: la entrada (destino) y la salida (origen) se invierten
            $this->movimiento->revertirPorReferencia(
                MovimientoDineroService::TIPO_TRANSFERENCIA_ENTRADA,
                MovimientoDineroService::REF_TRANSFERENCIA_DINERO,
                $transferencia->id_transferencia_dinero,
                'Anulación de transferencia ' . $transferencia->numero
            );
            $this->movimiento->revertirPorReferencia(
                MovimientoDineroService::TIPO_TRANSFERENCIA_SALIDA,
                MovimientoDineroService::REF_TRANSFERENCIA_DINERO,
                $transferencia->id_transferencia_dinero,
                'Anulación de transferencia ' . $transferencia->numero
            );

            $transferencia->update(['estado' => 0]);

            DB::commit();

            return redirect()->route('admin.transferencias-dinero.index')
                ->with('success', 'Transferencia de dinero anulada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
