<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Vendedor;
use Illuminate\Http\Request;

class VendedorController extends Controller
{
    public function index()
    {
        $vendedores = Vendedor::with('usuario')->orderBy('nombre', 'asc')->get();
        $usuarios = Usuario::where('estado', 1)->orderBy('nombres', 'asc')->get();

        return view('admin.vendedores.index', compact('vendedores', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'nullable|exists:usuarios,id_usuario',
            'nombre' => 'required|string|max:150',
            'documento' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:30',
            'correo' => 'nullable|email|max:150',
        ]);

        try {
            $vendedor = Vendedor::create([
                'id_usuario' => $request->id_usuario ?: null,
                'nombre' => $request->nombre,
                'documento' => $request->documento,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'estado' => $request->estado ?? 1,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'id_vendedor' => $vendedor->id_vendedor,
                    'nombre' => $vendedor->nombre,
                ]);
            }

            return redirect()->route('admin.vendedores.index')
                ->with('success', 'Vendedor creado correctamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_usuario' => 'nullable|exists:usuarios,id_usuario',
            'nombre' => 'required|string|max:150',
            'documento' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:30',
            'correo' => 'nullable|email|max:150',
        ]);

        try {
            $vendedor = Vendedor::findOrFail($id);

            $vendedor->update([
                'id_usuario' => $request->id_usuario ?: null,
                'nombre' => $request->nombre,
                'documento' => $request->documento,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'estado' => $request->estado ?? 1,
            ]);

            return redirect()->route('admin.vendedores.index')
                ->with('success', 'Vendedor actualizado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $vendedor = Vendedor::findOrFail($id);

        if ($vendedor->cajas()->exists()) {
            return back()->with('error', 'No se puede eliminar: el vendedor tiene cajas asociadas');
        }

        $vendedor->delete();

        return back()->with('success', 'Vendedor eliminado');
    }
}
