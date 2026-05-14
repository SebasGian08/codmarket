<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promocion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromocionController extends Controller
{
    public function index()
    {
        $promociones = Promocion::orderBy('orden', 'asc')->get();

        return view('admin.promociones.index', compact('promociones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'nullable|max:150',
            'subtitulo' => 'nullable|max:150',
            'descripcion' => 'nullable',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'imagen_mobile' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {

            $imagen = uploadImageOptimized($request->file('imagen'), 'promociones');

            $imagenMobile = $request->hasFile('imagen_mobile')
                ? uploadImageOptimized($request->file('imagen_mobile'), 'promociones')
                : null;

            Promocion::create([
                'titulo' => $request->titulo,
                'subtitulo' => $request->subtitulo,
                'descripcion' => $request->descripcion,
                'imagen' => $imagen,
                'imagen_mobile' => $imagenMobile,
                'enlace' => $request->enlace,
                'texto_boton' => $request->texto_boton,
                'color_texto' => $request->color_texto ?? '#ffffff',
                'orden' => $request->orden ?? 0,
                'estado' => $request->estado ?? 1,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
            ]);

            DB::commit();

            return redirect()->route('admin.promociones.index')
                ->with('success', 'Promoción creada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $promocion = Promocion::findOrFail($id);

        DB::beginTransaction();

        try {

            $imagen = $promocion->imagen;
            $imagenMobile = $promocion->imagen_mobile;

            if ($request->hasFile('imagen')) {
                if ($promocion->imagen && file_exists(public_path($promocion->imagen))) {
                    unlink(public_path($promocion->imagen));
                }

                $imagen = uploadImageOptimized($request->file('imagen'), 'promociones');
            }

            if ($request->hasFile('imagen_mobile')) {
                if ($promocion->imagen_mobile && file_exists(public_path($promocion->imagen_mobile))) {
                    unlink(public_path($promocion->imagen_mobile));
                }

                $imagenMobile = uploadImageOptimized($request->file('imagen_mobile'), 'promociones');
            }

            $promocion->update([
                'titulo' => $request->titulo,
                'subtitulo' => $request->subtitulo,
                'descripcion' => $request->descripcion,
                'imagen' => $imagen,
                'imagen_mobile' => $imagenMobile,
                'enlace' => $request->enlace,
                'texto_boton' => $request->texto_boton,
                'color_texto' => $request->color_texto ?? '#ffffff',
                'orden' => $request->orden ?? 0,
                'estado' => $request->estado ?? 1,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
            ]);

            DB::commit();

            return redirect()->route('admin.promociones.index')
                ->with('success', 'Promoción actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $promo = Promocion::findOrFail($id);

        if ($promo->imagen && file_exists(public_path($promo->imagen))) {
            unlink(public_path($promo->imagen));
        }

        if ($promo->imagen_mobile && file_exists(public_path($promo->imagen_mobile))) {
            unlink(public_path($promo->imagen_mobile));
        }

        $promo->delete();

        return back()->with('success', 'Promoción eliminada correctamente');
    }
}