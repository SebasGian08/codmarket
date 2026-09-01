<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MotivoDescuentoRequest;
use App\Models\MotivoDescuento;
use Illuminate\Support\Facades\DB;

class MotivoDescuentoController extends Controller
{
    public function index()
    {
        $motivos = MotivoDescuento::orderBy('aplica_a', 'asc')
            ->orderBy('nombre', 'asc')
            ->get();

        return view('admin.motivos-descuento.index', compact('motivos'));
    }

    public function store(MotivoDescuentoRequest $request)
    {
        try {
            DB::beginTransaction();

            MotivoDescuento::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'aplica_a' => $request->aplica_a,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.motivos-descuento.index')
                ->with('success', 'Motivo de descuento creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(MotivoDescuentoRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $motivo = MotivoDescuento::findOrFail($id);

            $motivo->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'aplica_a' => $request->aplica_a,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.motivos-descuento.index')
                ->with('success', 'Motivo de descuento actualizado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $motivo = MotivoDescuento::findOrFail($id);

        try {
            $motivo->delete();
            return back()->with('success', 'Motivo de descuento eliminado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'No se puede eliminar: está siendo usado en ventas');
        }
    }
}
