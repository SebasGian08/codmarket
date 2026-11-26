<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PermisoHelper;
use App\Http\Controllers\Controller;
use App\Models\MotivoDescuento;
use App\Models\ReglaDescuento;
use App\Models\TipoDescuento;
use App\Models\TipoVenta;
use Illuminate\Http\Request;

class DescuentoController extends Controller
{
    public function index(Request $request)
    {
        $puedeVerTiposVenta = PermisoHelper::tiene('tipos-venta.ver');
        $puedeVerMotivos = PermisoHelper::tiene('motivos-descuento.ver');
        $puedeVerReglas = PermisoHelper::tiene('reglas-descuento.ver');

        abort_unless($puedeVerTiposVenta || $puedeVerMotivos || $puedeVerReglas, 403);

        $pestanasDisponibles = [];
        if ($puedeVerTiposVenta) {
            $pestanasDisponibles[] = 'tipos-venta';
        }
        if ($puedeVerMotivos) {
            $pestanasDisponibles[] = 'motivos-descuento';
        }
        if ($puedeVerReglas) {
            $pestanasDisponibles[] = 'reglas-descuento';
        }

        $pestanaActiva = $request->query('tab');
        if (!in_array($pestanaActiva, $pestanasDisponibles, true)) {
            $pestanaActiva = $pestanasDisponibles[0];
        }

        $tipos = $puedeVerTiposVenta
            ? TipoVenta::withCount('reglas')->orderBy('nombre')->get()
            : collect();
        $motivos = $puedeVerMotivos
            ? MotivoDescuento::orderBy('aplica_a')->orderBy('nombre')->get()
            : collect();
        $reglas = $puedeVerReglas
            ? ReglaDescuento::with(['tipoVenta', 'tipoDescuento'])->orderBy('nombre')->get()
            : collect();
        $tiposVenta = $puedeVerReglas
            ? TipoVenta::where('estado', 1)->orderBy('nombre')->get()
            : collect();
        $tiposDescuento = $puedeVerReglas
            ? TipoDescuento::where('estado', 1)->orderBy('nombre')->get()
            : collect();

        return view('admin.descuentos.index', compact(
            'puedeVerTiposVenta',
            'puedeVerMotivos',
            'puedeVerReglas',
            'pestanaActiva',
            'tipos',
            'motivos',
            'reglas',
            'tiposVenta',
            'tiposDescuento'
        ));
    }
}
