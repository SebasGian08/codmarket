<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReglaDescuentoRequest;
use App\Models\ReglaDescuento;
use App\Models\TipoDescuento;
use App\Models\TipoVenta;
use Illuminate\Support\Facades\DB;

class ReglaDescuentoController extends Controller
{
    public function index()
    {
        $reglas = ReglaDescuento::with(['tipoVenta', 'tipoDescuento'])
            ->orderBy('nombre', 'asc')
            ->get();

        $tiposVenta = TipoVenta::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $tiposDescuento = TipoDescuento::where('estado', 1)->orderBy('nombre', 'asc')->get();

        return view('admin.reglas-descuento.index', compact('reglas', 'tiposVenta', 'tiposDescuento'));
    }

    public function store(ReglaDescuentoRequest $request)
    {
        try {
            DB::beginTransaction();

            ReglaDescuento::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'id_tipo_descuento' => $request->id_tipo_descuento,
                'valor' => $request->valor,
                'cantidad_min' => $request->cantidad_min,
                'cantidad_max' => $request->cantidad_max,
                'id_tipo_venta' => $request->id_tipo_venta,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.reglas-descuento.index')
                ->with('success', 'Regla de descuento creada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(ReglaDescuentoRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $regla = ReglaDescuento::findOrFail($id);

            $regla->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'id_tipo_descuento' => $request->id_tipo_descuento,
                'valor' => $request->valor,
                'cantidad_min' => $request->cantidad_min,
                'cantidad_max' => $request->cantidad_max,
                'id_tipo_venta' => $request->id_tipo_venta,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.reglas-descuento.index')
                ->with('success', 'Regla de descuento actualizada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $regla = ReglaDescuento::findOrFail($id);

        try {
            $regla->delete();
            return back()->with('success', 'Regla de descuento eliminada correctamente');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
