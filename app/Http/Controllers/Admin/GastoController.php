<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\CuentaBancaria;
use App\Models\Gasto;
use App\Models\Tienda;
use App\Models\TipoGasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GastoController extends Controller
{
    public function index()
    {
        $gastos = Gasto::with(['tipoGasto', 'tienda', 'cuentaBancaria', 'usuario'])
            ->orderBy('id_gasto', 'desc')
            ->get();

        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $tiposGasto = TipoGasto::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $cuentasBancarias = CuentaBancaria::where('estado', 1)->orderBy('nombre_banco', 'asc')->get();

        $cajasAbiertas = Caja::where('estado', 1)
            ->with('tienda')
            ->get();

        return view('admin.gastos.index', compact(
            'gastos', 'tiendas', 'tiposGasto', 'cuentasBancarias', 'cajasAbiertas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tipo_gasto' => 'required|exists:tipos_gastos,id_tipo_gasto',
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

            $num = generarNumeroDocumento('GAS', 'gastos', $request->id_tienda);

            $gasto = Gasto::create([
                'numero' => $num['numero'],
                'correlativo' => $num['correlativo'],
                'id_tipo_gasto' => $request->id_tipo_gasto,
                'id_tienda' => $request->id_tienda,
                'id_caja' => $request->id_caja,
                'id_cuenta_bancaria' => $request->id_cuenta_bancaria,
                'id_usuario' => auth()->id(),
                'fecha' => $request->fecha,
                'descripcion' => $request->descripcion,
                'monto' => $request->monto,
                'moneda' => 'PEN',
                'observacion' => $request->observacion,
                'estado' => 1,
            ]);

            DB::commit();

            return redirect()->route('admin.gastos.index')
                ->with('success', 'Gasto ' . $gasto->numero . ' registrado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function anular($id)
    {
        try {
            $gasto = Gasto::findOrFail($id);

            if ($gasto->estado == 0) {
                throw new \Exception('Este gasto ya está anulado');
            }

            $gasto->update(['estado' => 0]);

            return redirect()->route('admin.gastos.index')
                ->with('success', 'Gasto anulado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function detalle($id)
    {
        $gasto = Gasto::with(['tipoGasto', 'tienda', 'cuentaBancaria', 'usuario'])
            ->findOrFail($id);

        return view('admin.gastos.modals.detalle', compact('gasto'));
    }
}
