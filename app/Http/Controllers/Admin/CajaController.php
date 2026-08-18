<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\Tienda;
use App\Models\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
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
            });

        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $vendedores = Vendedor::where('estado', 1)
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

            $caja->update([
                'monto_cierre' => $request->monto_cierre,
                'fecha_cierre' => now(),
                'estado' => 0,
            ]);

            DB::commit();

            return redirect()->route('admin.cajas.index')
                ->with('success', 'Caja cerrada correctamente');
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
