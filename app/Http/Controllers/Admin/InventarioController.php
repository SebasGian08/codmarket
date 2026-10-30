<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Tienda;
use App\Exports\ConsultaStockExport;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class InventarioController extends Controller
{
    private const STOCK_BAJO_MAXIMO = 5;

    private function consultaStockQuery(array $filtros, bool $filtrarEstado = true): Builder
    {
        $query = DB::table('productos_variantes as pv')
            ->join('productos as p', 'p.id_producto', '=', 'pv.id_producto')
            ->crossJoin('tiendas as t')
            ->leftJoin('inventarios as i', function ($join) {
                $join->on('i.id_variante', '=', 'pv.id_variante')
                    ->on('i.id_tienda', '=', 't.id_tienda');
            })
            ->where('p.estado', 1)
            ->where('pv.estado', 1)
            ->where('t.estado', 1)
            ->select([
                'p.nombre as producto',
                'pv.sku',
                't.codigo as codigo_tienda',
                't.nombre as tienda',
            ])
            ->selectRaw(
                "COALESCE((SELECT GROUP_CONCAT(CONCAT(a.nombre, ': ', av.valor) ORDER BY a.nombre SEPARATOR ', ')
                    FROM variantes_atributos va
                    INNER JOIN atributos_valores av ON av.id_valor = va.id_valor
                    INNER JOIN atributos a ON a.id_atributo = av.id_atributo
                    WHERE va.id_variante = pv.id_variante), 'Única') as variante"
            )
            ->selectRaw('COALESCE(i.cantidad, 0) as stock')
            ->selectRaw(
                "CASE
                    WHEN COALESCE(i.cantidad, 0) = 0 THEN 'Agotado'
                    WHEN COALESCE(i.cantidad, 0) <= ? THEN 'Stock bajo'
                    ELSE 'Disponible'
                END as estado_stock",
                [self::STOCK_BAJO_MAXIMO]
            );

        if (!empty($filtros['buscar'])) {
            $termino = trim($filtros['buscar']);
            $query->where(function ($subquery) use ($termino) {
                $subquery->where('p.nombre', 'like', '%' . $termino . '%')
                    ->orWhere('pv.sku', 'like', '%' . $termino . '%')
                    ->orWhere('pv.codigo_barras', 'like', '%' . $termino . '%');
            });
        }

        if (!empty($filtros['id_tienda'])) {
            $query->where('t.id_tienda', $filtros['id_tienda']);
        }

        if ($filtrarEstado && !empty($filtros['estado_stock'])) {
            if ($filtros['estado_stock'] === 'agotado') {
                $query->whereRaw('COALESCE(i.cantidad, 0) = 0');
            } elseif ($filtros['estado_stock'] === 'bajo') {
                $query->whereRaw('COALESCE(i.cantidad, 0) BETWEEN 1 AND ?', [self::STOCK_BAJO_MAXIMO]);
            } elseif ($filtros['estado_stock'] === 'disponible') {
                $query->where('i.cantidad', '>', self::STOCK_BAJO_MAXIMO);
            }
        }

        return $query->orderBy('p.nombre')->orderBy('pv.sku')->orderBy('t.nombre');
    }

    private function validarFiltrosConsulta(Request $request): array
    {
        $request->validate([
            'buscar' => 'nullable|string|max:150',
            'id_tienda' => 'nullable|integer|exists:tiendas,id_tienda',
            'estado_stock' => 'nullable|in:agotado,bajo,disponible',
            'por_pagina' => 'nullable|integer|in:10,25,50,100',
        ]);

        return [
            'buscar' => trim((string) $request->query('buscar', '')),
            'id_tienda' => $request->query('id_tienda'),
            'estado_stock' => $request->query('estado_stock'),
            'por_pagina' => (int) $request->query('por_pagina', 10),
        ];
    }

    public function consulta(Request $request)
    {
        $filtros = $this->validarFiltrosConsulta($request);
        $consultaCompleta = $this->consultaStockQuery($filtros, false);
        $consultaFiltrada = $this->consultaStockQuery($filtros);

        $resumen = DB::query()
            ->fromSub((clone $consultaCompleta)->reorder(), 'consulta_stock')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) as agotados')
            ->selectRaw('SUM(CASE WHEN stock BETWEEN 1 AND ? THEN 1 ELSE 0 END) as stock_bajo', [self::STOCK_BAJO_MAXIMO])
            ->selectRaw('SUM(CASE WHEN stock > ? THEN 1 ELSE 0 END) as disponibles', [self::STOCK_BAJO_MAXIMO])
            ->selectRaw('COALESCE(SUM(stock), 0) as unidades')
            ->first();

        $registros = $consultaFiltrada->paginate($filtros['por_pagina'])->withQueryString();
        $tiendas = Tienda::where('estado', 1)->orderBy('nombre')->get();

        return view('admin.inventarios.consulta', compact('filtros', 'resumen', 'registros', 'tiendas'));
    }

    public function exportarConsulta(Request $request)
    {
        $filtros = $this->validarFiltrosConsulta($request);

        return Excel::download(
            new ConsultaStockExport($this->consultaStockQuery($filtros)),
            'Consulta_stock_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function index()
    {
        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();

        $productos = Producto::with([
            'variantes.atributos.atributo',
            'variantes.imagenes',
            'imagenes'
        ])
            ->where('estado', 1)
            ->orderBy('orden')
            ->orderByDesc('id_producto')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id_producto,
                    'nombre' => $p->nombre,
                    'imagen' => $p->imagen_principal_url,
                    'variantes' => $p->variantes->map(function ($v) {
                        $img = $v->imagenes->where('principal', 1)->first() ?? $v->imagenes->first();
                        return [
                            'id' => $v->id_variante,
                            'sku' => $v->sku,
                            'precio' => $v->precio,
                            'precio_oferta' => $v->precio_oferta,
                            'costo' => $v->costo,
                            'estado' => $v->estado,
                            'imagen' => $img ? asset($img->url) : null,
                            'atributos' => $v->atributos->map(function ($a) {
                                return [
                                    'atributo' => $a->atributo->nombre ?? '',
                                    'valor' => $a->valor,
                                ];
                            })->values(),
                        ];
                    })->values(),
                ];
            });

        $stockPorTienda = Inventario::get()
            ->groupBy('id_variante')
            ->map(function ($grupo) {
                return $grupo->pluck('cantidad', 'id_tienda')->toArray();
            })
            ->toArray();

        return view('admin.inventarios.index', compact(
            'tiendas',
            'productos',
            'stockPorTienda'
        ));
    }
}
