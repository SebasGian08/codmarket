<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TipoDocumento;

class ProveedorController extends Controller
{
public function index(Request $request)
    {
        $filtros = [
            'nombre' => $request->query('nombre'),
        ];

        $query = Proveedor::with('tipoDocumento');

        if (!empty($filtros['nombre'])) {
            $query->where(function ($q) use ($filtros) {
                $q->where('nombre', 'like', '%' . $filtros['nombre'] . '%')
                    ->orWhere('numero_documento', 'like', '%' . $filtros['nombre'] . '%')
                    ->orWhere('correo', 'like', '%' . $filtros['nombre'] . '%');
            });
        }

        $proveedores = $query->orderBy('nombre', 'asc')->get();
        $tipos = TipoDocumento::all();

        return view('admin.proveedores.index', compact('proveedores', 'tipos', 'filtros'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:150'
        ]);

        DB::beginTransaction();

        try {

            Proveedor::create([
                'nombre' => $request->nombre,
                'id_tipo_documento' => $request->id_tipo_documento,
                'numero_documento' => $request->numero_documento,
                'contacto' => $request->contacto,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'direccion' => $request->direccion,
                'estado' => 1
            ]);

            DB::commit();

            return redirect()->route('admin.proveedores.index')
                ->with('success', 'Proveedor creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $proveedor->update([
            'nombre' => $request->nombre,
            'id_tipo_documento' => $request->id_tipo_documento,
            'numero_documento' => $request->numero_documento,
            'contacto' => $request->contacto,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'direccion' => $request->direccion,
            'estado' => $request->estado ?? 1
        ]);

        return redirect()->route('admin.proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente');
    }

    public function destroy($id)
    {
        Proveedor::findOrFail($id)->delete();

        return back()->with('delete', 'Proveedor eliminado');
    }
}