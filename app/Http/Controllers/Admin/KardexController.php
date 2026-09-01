<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movimiento;
use App\Models\MovimientoTipo;
use App\Models\Producto;
use App\Models\ProductoVariante;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class KardexController extends Controller
{
    /**
     * Listado de movimientos de inventario (KARDEX) con filtros y saldo acumulado.
     */
    public function index(Request $request)
    {
        $idVariante = $request->get('id_variante');
        $idTienda = $request->get('id_tienda');
        $idTipo = $request->get('id_tipo_movimiento');
        $fechaDesde = $request->get('fecha_desde');
        $fechaHasta = $request->get('fecha_hasta');

        // ============ SALDO ACUMULADO (sobre el historial de variante+tienda) ============
        // El saldo en cada fila refleja el stock resultante tras ese movimiento, por
        // variante+tienda, en orden cronológico. Los filtros de tipo/fecha solo ocultan
        // filas, no alteran el saldo.
        $query = Movimiento::with(['variante.producto', 'variante.atributos.atributo', 'tipoMovimiento', 'tienda', 'usuario']);

        if ($idVariante) {
            $query->where('id_variante', $idVariante);
        }
        if ($idTienda) {
            $query->where('id_tienda', $idTienda);
        }

        $movimientos = $query->orderBy('fecha', 'asc')->orderBy('id_movimiento', 'asc')->get();

        $saldoPorClave = [];
        $filas = [];
        foreach ($movimientos as $m) {
            $clave = $m->id_variante . ':' . $m->id_tienda;
            $saldoPorClave[$clave] = ($saldoPorClave[$clave] ?? 0) + $m->cantidad;
            $filas[] = [
                'mov' => $m,
                'saldo' => $saldoPorClave[$clave],
            ];
        }

        // Aplicar filtros de tipo y rango de fechas sobre las filas ya saldadas
        $filas = collect($filas)->filter(function ($fila) use ($idTipo, $fechaDesde, $fechaHasta) {
            /** @var \App\Models\Movimiento $m */
            $m = $fila['mov'];

            if ($idTipo && (int) $m->id_tipo_movimiento !== (int) $idTipo) {
                return false;
            }
            if ($fechaDesde && $m->fecha && $m->fecha->startOfDay()->lt(\Carbon\Carbon::parse($fechaDesde)->startOfDay())) {
                return false;
            }
            if ($fechaHasta && $m->fecha && $m->fecha->startOfDay()->gt(\Carbon\Carbon::parse($fechaHasta)->endOfDay())) {
                return false;
            }

            return true;
        })->values();

        // Paginación manual del resultado
        $perPage = 30;
        $page = max(1, (int) $request->get('page', 1));
        $total = $filas->count();
        $items = $filas->slice(($page - 1) * $perPage, $perPage)->values();

        $movimientosPag = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // ============ DATOS PARA FILTROS ============
        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $tiposMovimiento = MovimientoTipo::where('estado', 1)->orderBy('nombre', 'asc')->get();

        $productos = Producto::where('estado', 1)
            ->orderBy('nombre', 'asc')
            ->with(['variantes' => function ($q) {
                $q->orderBy('sku', 'asc')->with('atributos.atributo');
            }])
            ->get(['id_producto', 'nombre']);

        $varianteSeleccionada = $idVariante
            ? ProductoVariante::with('producto', 'atributos.atributo')->find($idVariante)
            : null;

        $filtros = compact('idVariante', 'idTienda', 'idTipo', 'fechaDesde', 'fechaHasta');

        return view('admin.kardex.index', compact(
            'movimientosPag',
            'tiendas',
            'tiposMovimiento',
            'productos',
            'varianteSeleccionada',
            'filtros'
        ));
    }
}
