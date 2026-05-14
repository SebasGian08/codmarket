<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Atributo;
use App\Models\AtributoValor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtributoValorController extends Controller
{
    public function index()
    {
        $atributos = Atributo::with('valores')->get();
        return view('admin.atributos_valores.index', compact('atributos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_atributo' => 'required|exists:atributos,id_atributo',
            'valor' => 'required|max:100'
        ]);

        try {
            DB::beginTransaction();

            AtributoValor::create([
                'id_atributo' => $request->id_atributo,
                'valor' => $request->valor
            ]);

            DB::commit();

            return back()->with('success', 'Valor agregado');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $valor = AtributoValor::findOrFail($id);

        $valor->update([
            'valor' => $request->valor
        ]);

        return back()->with('success', 'Valor actualizado');
    }

    public function destroy($id)
    {
        AtributoValor::findOrFail($id)->delete();
        return back()->with('delete', 'Valor eliminado');
    }
}