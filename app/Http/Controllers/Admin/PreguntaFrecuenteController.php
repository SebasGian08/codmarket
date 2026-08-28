<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreguntaFrecuente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreguntaFrecuenteController extends Controller
{
    public function index()
    {
        $preguntas = PreguntaFrecuente::orderBy('orden', 'asc')->get();
        return view('admin.preguntas-frecuentes.index', compact('preguntas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pregunta' => 'required|string|max:255',
            'respuesta' => 'required|string',
            'orden' => 'nullable|integer|min:0',
            'estado' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            PreguntaFrecuente::create([
                'pregunta' => $request->pregunta,
                'respuesta' => $request->respuesta,
                'orden' => $request->orden ?? 0,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.preguntas.index')
                ->with('success', 'Pregunta creada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pregunta' => 'required|string|max:255',
            'respuesta' => 'required|string',
            'orden' => 'nullable|integer|min:0',
            'estado' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $pregunta = PreguntaFrecuente::findOrFail($id);

            $pregunta->update([
                'pregunta' => $request->pregunta,
                'respuesta' => $request->respuesta,
                'orden' => $request->orden ?? 0,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.preguntas.index')
                ->with('success', 'Pregunta actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $pregunta = PreguntaFrecuente::findOrFail($id);
        $pregunta->delete();

        return back()->with('success', 'Pregunta eliminada');
    }
}
