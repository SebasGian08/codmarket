<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\CuentaBancaria;
use App\Models\IngresoEconomico;
use App\Models\Tienda;
use App\Models\TipoIngresoEconomico;
use App\Services\MovimientoDineroService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngresoEconomicoController extends Controller
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
            'id_tipo_ingreso_economico' => $request->query('id_tipo_ingreso_economico'),
            'estado' => $request->query('estado'),
            'fecha_desde' => $request->query('fecha_desde'),
            'fecha_hasta' => $request->query('fecha_hasta'),
        ];

        $query = IngresoEconomico::with(['tipoIngresoEconomico', 'tienda', 'caja', 'cuentaBancaria', 'usuario']);

        if (!empty($filtros['numero'])) {
            $query->where('numero', 'like', '%' . $filtros['numero'] . '%');
        }

        if (!empty($filtros['id_tienda'])) {
            $query->where('id_tienda', $filtros['id_tienda']);
        }

        if (!empty($filtros['id_tipo_ingreso_economico'])) {
            $query->where('id_tipo_ingreso_economico', $filtros['id_tipo_ingreso_economico']);
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

        $ingresos = $query->orderBy('id_ingreso_economico', 'desc')->get();

        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $tiposIngreso = TipoIngresoEconomico::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $cuentasBancarias = CuentaBancaria::where('estado', 1)->orderBy('nombre_banco', 'asc')->get();
        $cajasAbiertas = Caja::where('estado', 1)->with('tienda')->get();

        return view('admin.ingresos-economicos.index', compact(
            'ingresos', 'tiendas', 'tiposIngreso', 'cuentasBancarias', 'cajasAbiertas', 'filtros'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tipo_ingreso_economico' => 'required|exists:tipos_ingresos_economicos,id_tipo_ingreso_economico',
            'id_tienda' => 'required|exists:tiendas,id_tienda',
            'id_caja' => 'nullable|exists:cajas,id_caja',
            'id_cuenta_bancaria' => 'nullable|exists:cuentas_bancarias,id_cuenta_bancaria',
            'fecha' => 'required|date',
            'descripcion' => 'required|string|max:500',
            'monto' => 'required|numeric|min:0.01',
            'observacion' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $idCaja = $request->id_caja;
            $idCuenta = $request->id_cuenta_bancaria;

            // Un ingreso afecta exactamente a UN destino financiero (caja XOR cuenta)
            if ($idCaja && $idCuenta) {
                throw new \Exception('Un ingreso no puede afectar caja y cuenta bancaria a la vez');
            }
            if (!$idCaja && !$idCuenta) {
                throw new \Exception('Debe indicar el destino del ingreso: caja o cuenta bancaria');
            }

            $num = generarNumeroDocumento('ING', 'ingresos_economicos', $request->id_tienda);

            $ingreso = IngresoEconomico::create([
                'numero' => $num['numero'],
                'correlativo' => $num['correlativo'],
                'id_tipo_ingreso_economico' => $request->id_tipo_ingreso_economico,
                'id_tienda' => $request->id_tienda,
                'id_caja' => $idCaja,
                'id_cuenta_bancaria' => $idCuenta,
                'id_usuario' => auth()->id(),
                'fecha' => $request->fecha,
                'descripcion' => $request->descripcion,
                'monto' => $request->monto,
                'moneda' => 'PEN',
                'observacion' => $request->observacion,
                'estado' => 1,
            ]);

            // Emitir el movimiento financiero (entrada de dinero)
            $this->movimiento->aplicarIngresoEconomico($ingreso);

            DB::commit();

            return redirect()->route('admin.ingresos-economicos.index')
                ->with('success', 'Ingreso económico ' . $ingreso->numero . ' registrado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function anular($id)
    {
        try {
            DB::beginTransaction();

            $ingreso = IngresoEconomico::findOrFail($id);

            if ($ingreso->estado == 0) {
                throw new \Exception('Este ingreso ya está anulado');
            }

            // Revertir el movimiento financiero (sacar el dinero de caja/cuenta)
            $this->movimiento->revertirPorReferencia(
                MovimientoDineroService::TIPO_INGRESO_ECONOMICO,
                MovimientoDineroService::REF_INGRESO_ECONOMICO,
                $ingreso->id_ingreso_economico,
                'Anulación de ingreso económico ' . $ingreso->numero
            );

            $ingreso->update(['estado' => 0]);

            DB::commit();

            return redirect()->route('admin.ingresos-economicos.index')
                ->with('success', 'Ingreso económico anulado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
