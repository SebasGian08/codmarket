<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoGasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoGastoController extends Controller
{
    public function index()
    {
        $tipos = TipoGasto::withCount('gastos')
            ->orderBy('nombre', 'asc')
            ->get();

        return view('admin.tipos-gastos.index', compact('tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'estado' => 'nullable|in:0,1',
        ]);

        try {
            DB::beginTransaction();

            TipoGasto::create([
                'nombre' => $request->nombre,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.tipos-gastos.index')
                ->with('success', 'Tipo de gasto creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'estado' => 'nullable|in:0,1',
        ]);

        try {
            DB::beginTransaction();

            $tipo = TipoGasto::findOrFail($id);

            $tipo->update([
                'nombre' => $request->nombre,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.tipos-gastos.index')
                ->with('success', 'Tipo de gasto actualizado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $tipo = TipoGasto::findOrFail($id);

        // No se puede eliminar si tiene gastos asociados
        if ($tipo->gastos()->exists()) {
            return back()->with('error', 'No se puede eliminar: el tipo de gasto tiene gastos asociados');
        }

        try {
            $tipo->delete();
            return back()->with('success', 'Tipo de gasto eliminado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
