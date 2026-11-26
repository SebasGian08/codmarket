<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = ['tab' => 'usuarios'];
        if ($request->filled('nombre')) {
            $query['nombre'] = $request->query('nombre');
        }
        if ($request->filled('id_rol')) {
            $query['id_rol'] = $request->query('id_rol');
        }

        return redirect()->route('admin.accesos.index', $query);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_rol'    => 'required|exists:roles,id_rol',
            'nombres'   => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email'     => 'required|email|unique:usuarios,email',
            'password'  => 'required|min:8',
        ]);

        Usuario::create([
            'id_rol'     => $request->id_rol,
            'nombres'    => $request->nombres,
            'apellidos'  => $request->apellidos,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'telefono'   => $request->telefono,
            'estado'     => 1,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.accesos.index', ['tab' => 'usuarios'])
            ->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $user = Usuario::findOrFail($id);

        $request->validate([
            'id_rol'    => 'required|exists:roles,id_rol',
            'nombres'   => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email'     => 'required|email|unique:usuarios,email,' . $id . ',id_usuario',
        ]);

        $user->fill($request->only(['id_rol', 'nombres', 'apellidos', 'email', 'telefono']));
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->updated_by = Auth::id();
        $user->save();

        return redirect()->route('admin.accesos.index', ['tab' => 'usuarios'])
            ->with('success', 'Usuario actualizado.');
    }

    public function destroy($id)
    {
        $user = Usuario::findOrFail($id);

        $user->deleted_by = Auth::id();
        $user->save();

        $user->delete();

        return redirect()->route('admin.accesos.index', ['tab' => 'usuarios'])
            ->with('delete', 'Usuario inhabilitado correctamente.');
    }

}