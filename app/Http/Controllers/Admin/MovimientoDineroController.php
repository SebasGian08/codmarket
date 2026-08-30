<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovimientoDinero;
use Illuminate\Http\Request;

class MovimientoDineroController extends Controller
{
    public function index(Request $request)
    {
        $query = MovimientoDinero::with(['tipoMovimientoDinero', 'caja', 'cuentaBancaria', 'metodoPago', 'usuarioRegistro'])
            ->orderBy('id_movimiento_dinero', 'desc');

        if ($request->get('tipo')) {
            $query->where('id_tipo_movimiento_dinero', $request->get('tipo'));
        }

        if ($request->get('destino')) {
            if ($request->get('destino') === 'caja') {
                $query->whereNotNull('id_caja');
            } elseif ($request->get('destino') === 'cuenta') {
                $query->whereNotNull('id_cuenta_bancaria');
            }
        }

        if ($request->get('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->get('fecha_desde'));
        }

        if ($request->get('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->get('fecha_hasta'));
        }

        $movimientos = $query->paginate(30)
            ->withQueryString();

        $tipos = \App\Models\TipoMovimientoDinero::where('estado', 1)
            ->orderBy('nombre', 'asc')
            ->get();

        return view('admin.movimientos-dinero.index', compact('movimientos', 'tipos'));
    }
}
