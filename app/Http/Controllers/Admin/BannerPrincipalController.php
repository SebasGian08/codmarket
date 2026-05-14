<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerPrincipal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class BannerPrincipalController extends Controller
{
    public function index()
    {
        $banners = BannerPrincipal::orderBy('orden', 'asc')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => $request->solo_imagen == 0 ? 'required|string|max:150' : 'nullable',
            'subtitulo' => 'nullable|string|max:150',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'imagen_mobile' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $imagen = null;
            $imagenMobile = null;

            if ($request->hasFile('imagen')) {
                $imagen = uploadImageOptimized($request->file('imagen'));
            }

            if ($request->hasFile('imagen_mobile')) {
                $imagenMobile = uploadImageOptimized($request->file('imagen_mobile'));
            }

            $imagenReferencial = null;

            if ($request->hasFile('imagen_referencial')) {
                $imagenReferencial = uploadImageOptimized($request->file('imagen_referencial'), 'banners', 800);
            }

            BannerPrincipal::create([
                'titulo' => $request->solo_imagen ? null : $request->titulo,
                'subtitulo' => $request->solo_imagen ? null : $request->subtitulo,
                'descripcion' => $request->solo_imagen ? null : $request->descripcion,
                'imagen' => $imagen,
                'imagen_mobile' => $imagenMobile,
                'imagen_referencial' => $imagenReferencial,
                'enlace' => $request->solo_imagen ? null : $request->enlace,
                'texto_boton' => $request->solo_imagen ? null : $request->texto_boton,
                'orden' => $request->orden ?? 0,
                'estado' => $request->estado ?? 1,
                'solo_imagen' => $request->solo_imagen ?? 0,
                'fecha_inicio' => $request->fecha_inicio ? str_replace('T', ' ', $request->fecha_inicio) : null,
                'fecha_fin' => $request->fecha_fin ? str_replace('T', ' ', $request->fecha_fin) : null,
            ]);

            DB::commit();

            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => $request->solo_imagen == 0 ? 'required|string|max:150' : 'nullable',
            'subtitulo' => 'nullable|string|max:150',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'imagen_mobile' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $banner = BannerPrincipal::findOrFail($id);

            $imagen = $banner->imagen;
            $imagenMobile = $banner->imagen_mobile;

            if ($request->hasFile('imagen')) {
                $this->deleteImage($banner->imagen);
                $imagen = uploadImageOptimized($request->file('imagen'));
            }

            if ($request->hasFile('imagen_mobile')) {
                $this->deleteImage($banner->imagen_mobile); 
                $imagenMobile = uploadImageOptimized($request->file('imagen_mobile'));
            }

            $imagenReferencial = $banner->imagen_referencial;

            if ($request->hasFile('imagen_referencial')) {
                $this->deleteImage($banner->imagen_referencial);
                $imagenReferencial = uploadImageOptimized($request->file('imagen_referencial'), 'banners', 800);
            }

            $banner->update([
                'titulo' => $request->solo_imagen ? null : $request->titulo,
                'subtitulo' => $request->solo_imagen ? null : $request->subtitulo,
                'descripcion' => $request->solo_imagen ? null : $request->descripcion,
                'imagen' => $imagen,
                'imagen_mobile' => $imagenMobile,
                'imagen_referencial' => $imagenReferencial,
                'enlace' => $request->solo_imagen ? null : $request->enlace,
                'texto_boton' => $request->solo_imagen ? null : $request->texto_boton,
                'orden' => $request->orden ?? 0,
                'estado' => $request->estado ?? 1,
                'solo_imagen' => $request->solo_imagen ?? 0,
                'fecha_inicio' => $request->fecha_inicio ? str_replace('T', ' ', $request->fecha_inicio) : null,
                'fecha_fin' => $request->fecha_fin ? str_replace('T', ' ', $request->fecha_fin) : null,
            ]);

            DB::commit();

            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $banner = BannerPrincipal::findOrFail($id);

        $this->deleteImage($banner->imagen);
        $this->deleteImage($banner->imagen_mobile);

        $banner->delete();

        return back()->with('delete', 'Banner eliminado');
    }

    private function deleteImage($path)
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}