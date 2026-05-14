<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rol;

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
}