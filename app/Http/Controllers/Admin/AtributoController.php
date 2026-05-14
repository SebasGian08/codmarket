<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Atributo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtributoController extends Controller
{
    public function index()
    {
        $atributos = Atributo::with('valores')->get();
        return view('admin.atributos.index', compact('atributos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255'
        ]);

        try {

            Atributo::create([
                'nombre' => $request->nombre
            ]);

            return back()->with('success', 'Atributo creado correctamente');

        } catch (\Exception $e) {

            return back()->with('error', 'Error al crear atributo: ' . $e->getMessage());

        }
    }

    public function update(Request $request, $id)
    {
        $atributo = Atributo::findOrFail($id);

        $atributo->update([
            'nombre' => $request->nombre
        ]);

        return back()->with('success', 'Atributo actualizado');
    }

    public function destroy($id)
    {
        Atributo::findOrFail($id)->delete();

        return back()->with('delete', 'Atributo eliminado');
    }
}