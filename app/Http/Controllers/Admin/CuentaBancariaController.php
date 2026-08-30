<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CuentaBancaria;
use App\Models\CuentaTipoCuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CuentaBancariaController extends Controller
{
    public function index()
    {
        $cuentas = CuentaBancaria::with('tipoCuenta')
            ->orderBy('id_cuenta_bancaria', 'desc')
            ->get();

        $tiposCuenta = CuentaTipoCuenta::where('estado', 1)->orderBy('nombre', 'asc')->get();

        return view('admin.cuentas-bancarias.index', compact('cuentas', 'tiposCuenta'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_banco' => 'required|string|max:100',
            'tipo_cuenta' => 'nullable|exists:cuentas_tipo_cuenta,id_tipo_cuenta',
            'numero_cuenta' => 'nullable|string|max:50',
            'titular' => 'nullable|string|max:150',
            'saldo_inicial' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $cuenta = CuentaBancaria::create([
                'nombre_banco' => $request->nombre_banco,
                'tipo_cuenta' => $request->tipo_cuenta,
                'numero_cuenta' => $request->numero_cuenta,
                'titular' => $request->titular,
                'saldo_actual' => $request->saldo_inicial ?: 0,
                'estado' => 1,
            ]);

            DB::commit();

            return redirect()->route('admin.cuentas-bancarias.index')
                ->with('success', 'Cuenta bancaria ' . $cuenta->nombre_banco . ' registrada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $cuenta = CuentaBancaria::findOrFail($id);

        $request->validate([
            'nombre_banco' => 'required|string|max:100',
            'tipo_cuenta' => 'nullable|exists:cuentas_tipo_cuenta,id_tipo_cuenta',
            'numero_cuenta' => 'nullable|string|max:50',
            'titular' => 'nullable|string|max:150',
        ]);

        $cuenta->update([
            'nombre_banco' => $request->nombre_banco,
            'tipo_cuenta' => $request->tipo_cuenta,
            'numero_cuenta' => $request->numero_cuenta,
            'titular' => $request->titular,
        ]);

        return redirect()->route('admin.cuentas-bancarias.index')
            ->with('success', 'Cuenta bancaria actualizada correctamente');
    }

    public function destroy($id)
    {
        $cuenta = CuentaBancaria::findOrFail($id);

        // No se puede eliminar si tiene pagos de venta asociados
        if ($cuenta->ventaPagos()->exists()) {
            return back()->with('error', 'No se puede eliminar: la cuenta tiene pagos de venta asociados');
        }

        // No se puede eliminar si tiene movimientos/destino de método de pago
        $enUsoMetodo = DB::table('metodos_pagos')->where('id_destino_pago', function ($q) {
            $q->select('id_destino_pago')->from('destinos_pago')->where('codigo', 'cuenta');
        })->exists();

        $cuenta->delete();

        return back()->with('success', 'Cuenta bancaria eliminada');
    }
}
