<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['benefits.details', 'plans.features'])
            ->whereNull('deleted_at')
            ->ordenado()
            ->get();

        $siguienteOrden = (int) \Illuminate\Support\Facades\DB::table('services')
            ->whereNull('deleted_at')
            ->max('orden') + 1;

        return view('admin.services.index', compact('services', 'siguienteOrden'));
    }

    private function uploadImage($file, $folder = 'services', $width = 1600, $quality = 85, $maxKb = 300)
    {
        return uploadImageOptimized($file, $folder, $width, $quality, $maxKb);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'grupo' => 'nullable|string|max:100',
            'orden' => 'required|integer|min:1',
            'descripcion' => 'required|string',
            'portada' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'imagen_portada' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'imagen_referencial' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'beneficios' => 'nullable|array',
            'beneficios.*' => 'array',
            'beneficios.*.titulo' => 'nullable|string|max:255',
            'beneficios.*.descripcion' => 'nullable|string',
            'beneficios.*.detalles' => 'nullable|array',
            'beneficios.*.detalles.*' => 'array',
            'beneficios.*.detalles.*.id' => 'nullable|integer|distinct',
            'beneficios.*.detalles.*.descripcion' => 'nullable|string|max:500',
            'beneficios.*.icono' => 'nullable|string|max:255',
            'beneficios.*.imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $portada = null;
            $imagenPortada = null;
            $imagenReferencial = null;

            if ($request->hasFile('portada')) {
                $portada = $this->uploadImage($request->file('portada'), 'services', 1920, 85, 400);
            }

            if ($request->hasFile('imagen_portada')) {
                $imagenPortada = $this->uploadImage($request->file('imagen_portada'), 'services', 1600, 85, 300);
            }

            if ($request->hasFile('imagen_referencial')) {
                $imagenReferencial = $this->uploadImage($request->file('imagen_referencial'), 'services', 1080, 85, 300);
            }

            $service = Service::create([
                'nombre' => $request->nombre,
                'grupo' => $request->filled('grupo') ? trim($request->grupo) : null,
                'orden' => $request->orden,
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

            $this->guardarBeneficios($service, $request);

            // PLANES
            if ($request->has('planes')) {
                foreach ($request->planes as $p) {
                    if (!empty($p['nombre'])) {
                        $plan = $service->plans()->create([
                            'nombre' => $p['nombre'],
                            'precio' => $p['precio'] ?? 0,
                            'descripcion' => $p['descripcion'] ?? null,
                            'destacado' => isset($p['destacado']) ? 1 : 0,
                        ]);

                        if (!empty($p['features'])) {
                            foreach ($p['features'] as $f) {
                                if (!empty($f)) {
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
            'grupo' => 'nullable|string|max:100',
            'orden' => 'required|integer|min:1',
            'descripcion' => 'required|string',
            'portada' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'imagen_portada' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'imagen_referencial' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'eliminar_portada' => 'nullable|boolean',
            'eliminar_imagen_portada' => 'nullable|boolean',
            'eliminar_imagen_referencial' => 'nullable|boolean',
            'beneficios' => 'nullable|array',
            'beneficios.*' => 'array',
            'beneficios.*.id' => 'nullable|integer|distinct',
            'beneficios.*.titulo' => 'nullable|string|max:255',
            'beneficios.*.descripcion' => 'nullable|string',
            'beneficios.*.detalles' => 'nullable|array',
            'beneficios.*.detalles.*' => 'array',
            'beneficios.*.detalles.*.id' => 'nullable|integer|distinct',
            'beneficios.*.detalles.*.descripcion' => 'nullable|string|max:500',
            'beneficios.*.icono' => 'nullable|string|max:255',
            'beneficios.*.imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'beneficios.*.eliminar_imagen' => 'nullable|boolean',
        ]);

        $transaccionConfirmada = false;

        try {
            DB::beginTransaction();

            $service = Service::findOrFail($id);
            $imagenesParaEliminar = [];
            $portada = $this->procesarImagen($request, 'portada', $service->portada, $imagenesParaEliminar, 1920, 400);
            $imagenPortada = $this->procesarImagen($request, 'imagen_portada', $service->imagen_portada, $imagenesParaEliminar, 1600, 300);
            $imagenReferencial = $this->procesarImagen($request, 'imagen_referencial', $service->imagen_referencial, $imagenesParaEliminar, 1080, 300);

            $service->update([
                'nombre' => $request->nombre,
                'grupo' => $request->filled('grupo') ? trim($request->grupo) : null,
                'orden' => $request->orden,
                'slug' => Str::slug($request->nombre),
                'descripcion' => $request->descripcion,
                'descripcion_portada' => $request->descripcion_portada,
                'descripcion_breve_portada' => $request->descripcion_breve_portada,
                'content' => $request->content,
                'portada' => $portada,
                'imagen_portada' => $imagenPortada,
                'imagen_referencial' => $imagenReferencial,
            ]);

            $imagenesParaEliminar = array_merge(
                $imagenesParaEliminar,
                $this->guardarBeneficios($service, $request, true)
            );

            // PLANES: Primero eliminamos las características hijas para evitar huérfanos
            foreach ($service->plans as $plan) {
                $plan->features()->forceDelete();
            }
            // Ahora eliminamos los planes por completo
            $service->plans()->forceDelete();

            if ($request->has('planes')) {
                foreach ($request->planes as $p) {
                    if (!empty($p['nombre'])) {
                        $plan = $service->plans()->create([
                            'nombre' => $p['nombre'],
                            'precio' => $p['precio'] ?? 0,
                            'descripcion' => $p['descripcion'] ?? null,
                            'destacado' => isset($p['destacado']) ? 1 : 0,
                        ]);

                        if (!empty($p['features'])) {
                            foreach ($p['features'] as $f) {
                                if (!empty($f)) {
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
            $transaccionConfirmada = true;

            foreach (array_unique($imagenesParaEliminar) as $imagen) {
                $this->eliminarImagenServicio($imagen);
            }

            return redirect()->route('admin.servicios.index')
                ->with('success', 'Servicio actualizado correctamente');

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            $mensaje = $transaccionConfirmada
                ? 'El servicio se actualizó, pero no se pudo eliminar una imagen anterior: ' . $e->getMessage()
                : $e->getMessage();

            return back()->with('error', $mensaje);
        }
    }

    private function procesarImagen(
        Request $request,
        string $campo,
        ?string $actual,
        array &$imagenesParaEliminar,
        int $width,
        int $maxKb
    ): ?string {
        if ($request->boolean("eliminar_{$campo}") && $actual) {
            $imagenesParaEliminar[] = $actual;
            $actual = null;
        }

        if (!$request->hasFile($campo)) {
            return $actual;
        }

        if ($actual) {
            $imagenesParaEliminar[] = $actual;
        }

        return $this->uploadImage($request->file($campo), 'services', $width, 85, $maxKb);
    }

    private function guardarBeneficios(Service $service, Request $request, bool $actualizar = false): array
    {
        $imagenesParaEliminar = [];
        $beneficiosExistentes = $actualizar
            ? $service->benefits()->get()->keyBy('id')
            : collect();
        $beneficiosProcesados = [];

        foreach ($request->input('beneficios', []) as $indice => $datos) {
            if (empty(trim($datos['titulo'] ?? ''))) {
                continue;
            }

            $beneficio = null;
            $beneficioId = $datos['id'] ?? null;

            if ($beneficioId !== null) {
                $beneficio = $beneficiosExistentes->get($beneficioId);

                if (!$beneficio) {
                    throw new \InvalidArgumentException('Uno de los beneficios no pertenece a este servicio.');
                }

                $beneficiosProcesados[] = (int) $beneficio->id;
            }

            $imagen = $beneficio ? $beneficio->imagen : null;

            if ($beneficio && $request->boolean("beneficios.{$indice}.eliminar_imagen") && $imagen) {
                $imagenesParaEliminar[] = $imagen;
                $imagen = null;
            }

            $archivo = $request->file("beneficios.{$indice}.imagen");
            if ($archivo) {
                if ($imagen) {
                    $imagenesParaEliminar[] = $imagen;
                }
                $imagen = $this->uploadImage($archivo, 'services', 1000, 85, 300);
            }

            $datosBeneficio = [
                'titulo' => trim($datos['titulo']),
                'descripcion' => $datos['descripcion'] ?? null,
                'icono' => $datos['icono'] ?? null,
                'imagen' => $imagen,
            ];

            if ($beneficio) {
                $beneficio->update($datosBeneficio);
            } else {
                $beneficio = $service->benefits()->create($datosBeneficio);
            }

            $detallesExistentes = $actualizar
                ? $beneficio->details()->get()->keyBy('id')
                : collect();
            $detallesProcesados = [];

            foreach ($datos['detalles'] ?? [] as $detalleDatos) {
                $detalleId = is_array($detalleDatos) ? ($detalleDatos['id'] ?? null) : null;
                $descripcionDetalle = is_array($detalleDatos)
                    ? ($detalleDatos['descripcion'] ?? '')
                    : $detalleDatos;
                $descripcionDetalle = trim((string) $descripcionDetalle);

                if ($descripcionDetalle === '') {
                    continue;
                }

                if ($detalleId !== null) {
                    $detalleExistente = $detallesExistentes->get($detalleId);

                    if (!$detalleExistente) {
                        throw new \InvalidArgumentException('Uno de los detalles no pertenece a este beneficio.');
                    }

                    $detalleExistente->update(['descripcion' => $descripcionDetalle]);
                    $detallesProcesados[] = (int) $detalleExistente->id;
                    continue;
                }

                $beneficio->details()->create(['descripcion' => $descripcionDetalle]);
            }

            foreach ($detallesExistentes as $detalleExistente) {
                if (!in_array((int) $detalleExistente->id, $detallesProcesados, true)) {
                    $detalleExistente->delete();
                }
            }
        }

        if ($actualizar) {
            foreach ($beneficiosExistentes as $beneficio) {
                if (!in_array((int) $beneficio->id, $beneficiosProcesados, true)) {
                    if ($beneficio->imagen) {
                        $imagenesParaEliminar[] = $beneficio->imagen;
                    }
                    $beneficio->delete();
                }
            }
        }

        return $imagenesParaEliminar;
    }

    private function eliminarImagenServicio(string $ruta): void
    {
        if (!str_starts_with($ruta, 'uploads/services/')) {
            return;
        }

        $enUso = Service::where('portada', $ruta)
            ->orWhere('imagen_portada', $ruta)
            ->orWhere('imagen_referencial', $ruta)
            ->exists()
            || \App\Models\ServiceBenefit::where('imagen', $ruta)->exists();

        if ($enUso) {
            return;
        }

        $directorioServicios = realpath(base_path('uploads/services'));
        $rutaArchivo = realpath(base_path($ruta));

        if (!$directorioServicios || !$rutaArchivo
            || !str_starts_with($rutaArchivo, $directorioServicios . DIRECTORY_SEPARATOR)
            || !is_file($rutaArchivo)) {
            return;
        }

        if (!unlink($rutaArchivo)) {
            throw new \RuntimeException("No se pudo eliminar el archivo \"{$ruta}\".");
        }
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return back()->with('delete', 'Servicio eliminado');
    }
}