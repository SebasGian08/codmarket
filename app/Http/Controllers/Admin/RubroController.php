<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rubro;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RubroController extends Controller
{
    public function index()
    {
        $rubros = Rubro::orderBy('orden', 'asc')->get();
        return view('admin.rubros.index', compact('rubros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $imagen = null;

            if ($request->hasFile('imagen')) {
                $imagen = uploadImageOptimized($request->file('imagen'), 'rubros');
            }

            Rubro::create([
                'nombre' => $request->nombre,
                'slug' => Str::slug($request->nombre),
                'descripcion' => $request->descripcion,
                'imagen' => $imagen,
                'orden' => $request->orden ?? 0,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.rubros.index')
                ->with('success', 'Rubro creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $rubro = Rubro::findOrFail($id);

            $imagen = $rubro->imagen;

            if ($request->hasFile('imagen')) {
                if ($rubro->imagen) {
                    $this->deleteImage($rubro->imagen);
                }

                $imagen = uploadImageOptimized($request->file('imagen'), 'rubros');
            }

            $rubro->update([
                'nombre' => $request->nombre,
                'slug' => Str::slug($request->nombre),
                'descripcion' => $request->descripcion,
                'imagen' => $imagen,
                'orden' => $request->orden ?? 0,
                'estado' => $request->estado ?? 1,
            ]);

            DB::commit();

            return redirect()->route('admin.rubros.index')
                ->with('success', 'Rubro actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $rubro = Rubro::findOrFail($id);

        if ($rubro->imagen) {
            $this->deleteImage($rubro->imagen);
        }

        $rubro->delete();

        return back()->with('success', 'Rubro eliminado');
    }

    private function deleteImage($path)
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}