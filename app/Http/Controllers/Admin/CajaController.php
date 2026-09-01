<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\Tienda;
use App\Models\Vendedor;
use App\Services\MovimientoDineroService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    protected $movimiento;

    public function __construct(MovimientoDineroService $movimiento)
    {
        $this->movimiento = $movimiento;
    }

    public function index()
    {
        $cajas = Caja::with(['tienda', 'usuario', 'vendedor'])
            ->withCount('ventas')
            ->orderBy('estado', 'desc')
            ->orderBy('id_caja', 'desc')
            ->get()
            ->each(function ($caja) {
                $caja->total_ventas = $caja->ventas()->where('estado', 1)->sum('total');
                $caja->nro_ventas = $caja->ventas()->where('estado', 1)->count();
                $caja->efectivo_esperado = $this->movimiento->efectivoEsperadoCaja($caja->id_caja);
            });

        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $vendedores = Vendedor::with('tiendas')->where('estado', 1)
            ->where('id_usuario', auth()->id())
            ->orderBy('nombre', 'asc')
            ->get();

        return view('admin.cajas.index', compact('cajas', 'tiendas', 'vendedores'));
    }

    public function abrir(Request $request)
    {
        $request->validate([
            'id_tienda' => 'required|exists:tiendas,id_tienda',
            'id_vendedor' => 'required|exists:vendedores,id_vendedor',
            'nombre' => 'nullable|string|max:100',
            'monto_apertura' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $yaAbierta = Caja::where('id_tienda', $request->id_tienda)
                ->where('id_vendedor', $request->id_vendedor)
                ->where('estado', 1)
                ->exists();

            if ($yaAbierta) {
                throw new \Exception('Esta tienda ya tiene una caja abierta para el vendedor seleccionado');
            }

            Caja::create([
                'id_tienda' => $request->id_tienda,
                'id_usuario' => auth()->id(),
                'id_vendedor' => $request->id_vendedor,
                'nombre' => $request->nombre ?: 'Caja Principal',
                'monto_apertura' => $request->monto_apertura,
                'fecha_apertura' => now(),
                'estado' => 1,
            ]);

            DB::commit();

            return redirect()->route('admin.cajas.index')
                ->with('success', 'Caja abierta correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function cerrar(Request $request, $id)
    {
        $request->validate([
            'monto_cierre' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $caja = Caja::findOrFail($id);

            if ($caja->estado == 0) {
                throw new \Exception('Esta caja ya está cerrada');
            }

            if ((int) $caja->id_usuario !== (int) auth()->id()) {
                throw new \Exception('Solo la persona que abrió esta caja puede cerrarla');
            }

            $contado = (float) $request->monto_cierre;
            $esperado = $this->movimiento->efectivoEsperadoCaja($caja->id_caja);

            // Arqueo: diferencia entre el conteo final y el esperado
            $diferencia = round($contado - $esperado, 2);

            $caja->update([
                'monto_cierre' => $contado,
                'monto_diferencia' => $diferencia,
                'fecha_cierre' => now(),
                'estado' => 0,
            ]);

            DB::commit();

            $mensaje = 'Caja cerrada correctamente. Efectivo esperado: S/ ' . number_format($esperado, 2) .
                ' | Contado: S/ ' . number_format($contado, 2);

            if (abs($diferencia) > 0.001) {
                $tipo = $diferencia > 0 ? 'sobrante' : 'faltante';
                $mensaje .= ' | ' . ucfirst($tipo) . ': S/ ' . number_format(abs($diferencia), 2);
            } else {
                $mensaje .= ' | Caja cuadrada.';
            }

            return redirect()->route('admin.cajas.index')
                ->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $caja = Caja::findOrFail($id);

        if ($caja->estado == 1) {
            return back()->with('error', 'Debes cerrar la caja antes de eliminarla');
        }

        if ($caja->ventas()->exists()) {
            return back()->with('error', 'No se puede eliminar: la caja tiene ventas asociadas');
        }

        $caja->delete();

        return back()->with('success', 'Caja eliminada');
    }
}
