<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index()
    {
        $users = Usuario::with('rol')->orderBy('id_usuario', 'desc')->get();
        $roles = Rol::all(); 

        return view('admin.users.index', compact('users', 'roles'));
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

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
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

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        $service->deleted_by = Auth::id();
        $service->save();

        $service->delete();

        return redirect()->route('admin.servicios.index')->with('delete', 'Servicio inhabilitado correctamente.');
    }

}