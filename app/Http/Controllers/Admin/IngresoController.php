<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingreso;
use App\Models\IngresoDetalle;
use App\Models\Proveedor;
use App\Models\Tienda;
use App\Services\InventarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngresoController extends Controller
{
    protected $inventario;

    public function __construct(InventarioService $inventario)
    {
        $this->inventario = $inventario;
    }

    public function index()
    {
        $ingresos = Ingreso::with(['tienda', 'proveedor', 'usuario'])
            ->orderBy('id_ingreso', 'desc')
            ->get();

        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $proveedores = Proveedor::where('estado', 1)->orderBy('nombre', 'asc')->get();

        $variantes = \App\Models\ProductoVariante::with(['producto', 'atributos.atributo'])
            ->where('estado', 1)
            ->get()
            ->map(function ($v) {
                $atributos = $v->atributos->map(function ($a) {
                    return [
                        'atributo' => $a->atributo->nombre ?? '',
                        'valor' => $a->valor,
                    ];
                })->values();

                return [
                    'id' => $v->id_variante,
                    'sku' => $v->sku,
                    'producto' => $v->producto->nombre ?? '',
                    'atributos' => $atributos,
                    'costo' => (float) $v->costo,
                ];
            })
            ->values();

        return view('admin.ingresos.index', compact('ingresos', 'tiendas', 'proveedores', 'variantes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:compra,ajuste',
            'id_proveedor' => 'required_if:tipo,compra|nullable|exists:proveedores,id_proveedor',
            'id_tienda' => 'required|exists:tiendas,id_tienda',
            'fecha' => 'required|date',
            'observacion' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.id_variante' => 'required|exists:productos_variantes,id_variante',
            'items.*.cantidad' => 'required|numeric|min:0.01',
            'items.*.costo' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $num = generarNumeroDocumento('ING', 'ingresos', $request->id_tienda);

            $ingreso = Ingreso::create([
                'numero' => $num['numero'],
                'correlativo' => $num['correlativo'],
                'tipo' => $request->tipo,
                'id_proveedor' => $request->tipo === 'compra' ? $request->id_proveedor : null,
                'id_tienda' => $request->id_tienda,
                'id_usuario' => auth()->id(),
                'fecha' => $request->fecha,
                'observacion' => $request->observacion,
                'estado' => 1,
            ]);

            foreach ($request->items as $item) {
                $cantidad = (int) $item['cantidad'];
                $costo = (float) ($item['costo'] ?? 0);

                IngresoDetalle::create([
                    'id_ingreso' => $ingreso->id_ingreso,
                    'id_variante' => $item['id_variante'],
                    'cantidad' => $cantidad,
                    'costo' => $costo,
                ]);

                $tipoMovimiento = $request->tipo === 'compra' ? 'ingreso' : 'ajuste';

                $this->inventario->aplicar(
                    $item['id_variante'],
                    $request->id_tienda,
                    $tipoMovimiento,
                    $cantidad,
                    $ingreso->id_ingreso,
                    auth()->id(),
                    'Ingreso ' . $ingreso->numero
                );
            }

            DB::commit();

            return redirect()->route('admin.ingresos.index')
                ->with('success', 'Ingreso ' . $ingreso->numero . ' registrado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function anular($id)
    {
        try {
            DB::beginTransaction();

            $ingreso = Ingreso::findOrFail($id);

            if ($ingreso->estado == 0) {
                throw new \Exception('Este ingreso ya está anulado');
            }

            $tipoMovimiento = $ingreso->tipo === 'compra' ? 'ajuste' : 'ajuste';

            foreach ($ingreso->detalle as $detalle) {
                $this->inventario->aplicar(
                    $detalle->id_variante,
                    $ingreso->id_tienda,
                    $tipoMovimiento,
                    -$detalle->cantidad,
                    $ingreso->id_ingreso,
                    auth()->id(),
                    'Anulación de ingreso ' . $ingreso->numero
                );
            }

            $ingreso->update(['estado' => 0]);

            DB::commit();

            return redirect()->route('admin.ingresos.index')
                ->with('success', 'Ingreso anulado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function detalle($id)
    {
        $ingreso = Ingreso::with(['tienda', 'proveedor', 'usuario', 'detalle.variante.producto'])
            ->findOrFail($id);

        return view('admin.ingresos.modals.detalle', compact('ingreso'));
    }
}
