<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrabajoRealizado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrabajoRealizadoController extends Controller
{
    public function index()
    {
        $trabajos = TrabajoRealizado::orderBy('orden', 'asc')->get();

        return view('admin.trabajos.index', compact('trabajos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|max:200',
            'cliente'     => 'nullable|string|max:150',
            'descripcion' => 'nullable',
            'imagen'      => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {

            DB::beginTransaction();

            $imagen = null;

            if ($request->hasFile('imagen')) {

                $imagen = uploadImageOptimized(
                    $request->file('imagen'),
                    'trabajos_realizados',
                    1200
                );

            }

            TrabajoRealizado::create([
                'titulo'      => $request->titulo,
                'cliente'     => $request->cliente,
                'descripcion' => $request->descripcion,
                'imagen'      => $imagen,
                'orden'       => $request->orden ?? 0,
                'estado'      => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.trabajos.index')
                ->with('success', 'Trabajo registrado correctamente');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo'      => 'required|string|max:200',
            'cliente'     => 'nullable|string|max:150',
            'descripcion' => 'nullable',
            'imagen'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {

            DB::beginTransaction();

            $trabajo = TrabajoRealizado::findOrFail($id);

            $imagen = $trabajo->imagen;

            if ($request->hasFile('imagen')) {

                $this->deleteImage($trabajo->imagen);

                $imagen = uploadImageOptimized(
                    $request->file('imagen'),
                    'trabajos_realizados',
                    1200
                );

            }

            $trabajo->update([
                'titulo'      => $request->titulo,
                'cliente'     => $request->cliente,
                'descripcion' => $request->descripcion,
                'imagen'      => $imagen,
                'orden'       => $request->orden ?? 0,
                'estado'      => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.trabajos.index')
                ->with('success', 'Trabajo actualizado correctamente');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $trabajo = TrabajoRealizado::findOrFail($id);

        $this->deleteImage($trabajo->imagen);

        $trabajo->delete();

        return back()->with('delete', 'Trabajo eliminado correctamente');
    }

    private function deleteImage($path)
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}