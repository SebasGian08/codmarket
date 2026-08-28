<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('tipoDocumento')->orderBy('nombre', 'asc')->get();
        $tiposDocumento = TipoDocumento::where('estado', 1)->orderBy('nombre', 'asc')->get();

        return view('admin.clientes.index', compact('clientes', 'tiposDocumento'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'id_tipo_documento' => 'nullable|exists:tipo_documento,id_tipo_documento',
            'numero_documento' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:30',
            'correo' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|max:5120',
            'logo' => 'nullable|image|max:5120',
        ]);

        try {
            $imagen = $request->hasFile('imagen')
                ? uploadImageOptimized($request->file('imagen'), 'clientes', 1200)
                : null;

            $logo = $request->hasFile('logo')
                ? uploadImageOptimized($request->file('logo'), 'clientes', 800)
                : null;

            $cliente = Cliente::create([
                'nombre' => $request->nombre,
                'id_tipo_documento' => $request->id_tipo_documento ?: null,
                'numero_documento' => $request->numero_documento,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'direccion' => $request->direccion,
                'imagen' => $imagen,
                'logo' => $logo,
                'estado' => $request->estado ?? 1,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'id_cliente' => $cliente->id_cliente,
                    'nombre' => $cliente->nombre,
                ]);
            }

            return redirect()->route('admin.clientes.index')
                ->with('success', 'Cliente creado correctamente');
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
            'nombre' => 'required|string|max:150',
            'id_tipo_documento' => 'nullable|exists:tipo_documento,id_tipo_documento',
            'numero_documento' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:30',
            'correo' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|max:5120',
            'logo' => 'nullable|image|max:5120',
        ]);

        try {
            $cliente = Cliente::findOrFail($id);

            $imagen = $request->hasFile('imagen')
                ? uploadImageOptimized($request->file('imagen'), 'clientes', 1200)
                : $cliente->imagen;

            $logo = $request->hasFile('logo')
                ? uploadImageOptimized($request->file('logo'), 'clientes', 800)
                : $cliente->logo;

            $cliente->update([
                'nombre' => $request->nombre,
                'id_tipo_documento' => $request->id_tipo_documento ?: null,
                'numero_documento' => $request->numero_documento,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'direccion' => $request->direccion,
                'imagen' => $imagen,
                'logo' => $logo,
                'estado' => $request->estado ?? 1,
            ]);

            return redirect()->route('admin.clientes.index')
                ->with('success', 'Cliente actualizado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        if ($cliente->es_varios) {
            return back()->with('error', 'El registro "Clientes Varios" no se puede eliminar');
        }

        $cliente->delete();

        return back()->with('success', 'Cliente eliminado');
    }
}
