<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\Permiso;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::orderBy('id_rol', 'desc')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255'
        ]);

        Rol::create([
            'nombre' => $request->nombre,
            'estado' => 1
        ]);

        return back()->with('success', 'Rol creado correctamente');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:255'
        ]);

        $rol = Rol::findOrFail($id);

        $rol->update([
            'nombre' => $request->nombre
        ]);

        return back()->with('success', 'Rol actualizado');
    }

    public function destroy($id)
    {
        Rol::findOrFail($id)->delete();

        return back()->with('delete', 'Rol eliminado');
    }

    public function permisos($id)
    {

        $rol = Rol::findOrFail($id);

        $permisos = Permiso::where('estado',1)
            ->orderBy('id_permiso')
            ->get();


        $permisosAsignados = $rol->permisos
            ->pluck('id_permiso')
            ->toArray();


        return view('admin.roles.permisos',compact(
            'rol',
            'permisos',
            'permisosAsignados'
        ));
    }

    public function guardarPermisos(Request $request,$id)
    {

        $rol = Rol::findOrFail($id);


        $rol->permisos()->sync(
            $request->permisos ?? []
        );


        return redirect()
            ->route('admin.roles.index')
            ->with('success','Permisos asignados correctamente');

    }
}