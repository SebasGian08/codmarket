<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TiendaController extends Controller
{
    public function index()
    {
        $tiendas = Tienda::withCount('cajas')->orderBy('es_principal', 'desc')->orderBy('nombre', 'asc')->get();

        return view('admin.tiendas.index', compact('tiendas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:10|unique:tiendas,codigo',
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:30',
        ]);

        try {
            DB::beginTransaction();

            $esPrincipal = $request->has('es_principal') ? 1 : 0;

            if ($esPrincipal) {
                Tienda::where('es_principal', 1)->update(['es_principal' => 0]);
            }

            Tienda::create([
                'codigo' => strtoupper($request->codigo),
                'nombre' => $request->nombre,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'es_principal' => $esPrincipal,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.tiendas.index')
                ->with('success', 'Tienda creada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'codigo' => 'required|string|max:10|unique:tiendas,codigo,' . $id . ',id_tienda',
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:30',
        ]);

        try {
            DB::beginTransaction();

            $tienda = Tienda::findOrFail($id);

            $esPrincipal = $request->has('es_principal') ? 1 : 0;

            if ($esPrincipal) {
                Tienda::where('es_principal', 1)
                    ->where('id_tienda', '!=', $id)
                    ->update(['es_principal' => 0]);
            }

            $tienda->update([
                'codigo' => strtoupper($request->codigo),
                'nombre' => $request->nombre,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'es_principal' => $esPrincipal,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.tiendas.index')
                ->with('success', 'Tienda actualizada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $tienda = Tienda::findOrFail($id);

        if ($tienda->cajas()->where('estado', 1)->exists()) {
            return back()->with('error', 'No se puede eliminar: la tienda tiene cajas abiertas');
        }

        $tienda->delete();

        return back()->with('success', 'Tienda eliminada');
    }
}
