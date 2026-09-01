<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoVentaRequest;
use App\Models\TipoVenta;
use Illuminate\Support\Facades\DB;

class TipoVentaController extends Controller
{
    public function index()
    {
        $tipos = TipoVenta::withCount('reglas')
            ->orderBy('nombre', 'asc')
            ->get();

        return view('admin.tipos-venta.index', compact('tipos'));
    }

    public function store(TipoVentaRequest $request)
    {
        try {
            DB::beginTransaction();

            TipoVenta::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.tipos-venta.index')
                ->with('success', 'Tipo de venta creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(TipoVentaRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $tipo = TipoVenta::findOrFail($id);

            $tipo->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.tipos-venta.index')
                ->with('success', 'Tipo de venta actualizado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $tipo = TipoVenta::findOrFail($id);

        try {
            $tipo->delete();
            return back()->with('success', 'Tipo de venta eliminado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'No se puede eliminar: tiene reglas o ventas asociadas');
        }
    }
}
