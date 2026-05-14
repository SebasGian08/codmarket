<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['benefits', 'plans.features'])
            ->whereNull('deleted_at')
            ->orderBy('id_service', 'desc')
            ->get();
            
        return view('admin.services.index', compact('services'));
    }

    private function uploadImage($file, $folder = 'services')
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $destinationPath = base_path('uploads/' . $folder);
        $file->move($destinationPath, $fileName);
        return 'uploads/' . $folder . '/' . $fileName;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'portada' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'imagen_portada' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'imagen_referencial' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        try {
            DB::beginTransaction();

            $portada = null;
            $imagenPortada = null;
            $imagenReferencial = null;

            if ($request->hasFile('portada')) {
                $portada = $this->uploadImage($request->file('portada'));
            }

            if ($request->hasFile('imagen_portada')) {
                $imagenPortada = $this->uploadImage($request->file('imagen_portada'));
            }

            if ($request->hasFile('imagen_referencial')) {
                $imagenReferencial = $this->uploadImage($request->file('imagen_referencial'));
            }

            $service = Service::create([
                'nombre' => $request->nombre,
                'slug' => Str::slug($request->nombre),
                'descripcion' => $request->descripcion,
                'descripcion_portada' => $request->descripcion_portada,
                'descripcion_breve_portada' => $request->descripcion_breve_portada,
                'content' => $request->content,

                'portada' => $portada,
                'imagen_portada' => $imagenPortada,
                'imagen_referencial' => $imagenReferencial,

                'estado' => 1,
            ]);

            // BENEFICIOS
            if ($request->has('beneficios')) {
                foreach ($request->beneficios as $b) {
                    if (!empty($b['titulo'])) {
                        $service->benefits()->create($b);
                    }
                }
            }

            // PLANES
            if ($request->has('planes')) {
                foreach ($request->planes as $p) {
                    if (!empty($p['nombre'])) {

                        $plan = $service->plans()->create([
                            'nombre' => $p['nombre'],
                            'precio' => $p['precio'] ?? 0,
                            'descripcion' => $p['descripcion'],
                            'destacado' => isset($p['destacado']) ? 1 : 0,
                        ]);

                        if (!empty($p['features'])) {
                            foreach ($p['features'] as $f) {
                                if ($f) {
                                    $plan->features()->create([
                                        'descripcion' => $f
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.servicios.index')
                ->with('success', 'Servicio creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'portada' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'imagen_portada' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'imagen_referencial' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        try {
            DB::beginTransaction();

            $service = Service::findOrFail($id);

            $portada = $service->portada;
            $imagenPortada = $service->imagen_portada;
            $imagenReferencial = $service->imagen_referencial;

            if ($request->hasFile('portada')) {
                $portada = $this->uploadImage($request->file('portada'));
            }

            if ($request->hasFile('imagen_portada')) {
                $imagenPortada = $this->uploadImage($request->file('imagen_portada'));
            }

            if ($request->hasFile('imagen_referencial')) {
                $imagenReferencial = $this->uploadImage($request->file('imagen_referencial'));
            }

            $service->update([
                'nombre' => $request->nombre,
                'slug' => Str::slug($request->nombre),
                'descripcion' => $request->descripcion,
                'descripcion_portada' => $request->descripcion_portada,
                'descripcion_breve_portada' => $request->descripcion_breve_portada,
                'content' => $request->content,

                'portada' => $portada,
                'imagen_portada' => $imagenPortada,
                'imagen_referencial' => $imagenReferencial,
            ]);

            // BENEFICIOS
            $service->benefits()->delete();
            if ($request->has('beneficios')) {
                foreach ($request->beneficios as $b) {
                    if (!empty($b['titulo'])) {
                        $service->benefits()->create($b);
                    }
                }
            }

            // PLANES
            $service->plans()->delete();
            if ($request->has('planes')) {
                foreach ($request->planes as $p) {
                    if (!empty($p['nombre'])) {

                        $plan = $service->plans()->create([
                            'nombre' => $p['nombre'],
                            'precio' => $p['precio'] ?? 0,
                            'descripcion' => $p['descripcion'],
                            'destacado' => isset($p['destacado']) ? 1 : 0,
                        ]);

                        if (!empty($p['features'])) {
                            foreach ($p['features'] as $f) {
                                if ($f) {
                                    $plan->features()->create([
                                        'descripcion' => $f
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.servicios.index')
                ->with('success', 'Servicio actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return back()->with('delete', 'Servicio eliminado');
    }
}