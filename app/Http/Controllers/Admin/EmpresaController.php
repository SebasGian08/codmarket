<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresa = Empresa::first();
        return view('admin.empresa.index', compact('empresa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:200',
            'correo' => 'nullable|email',
            'beneficios' => 'nullable|array|max:4',
            'beneficios.*.titulo' => 'nullable|string|max:150',
            'beneficios.*.descripcion' => 'nullable|string|max:1000',
            'beneficios.*.enlace' => 'nullable|string|max:2048',
            'beneficios.*.texto_boton' => 'nullable|string|max:80',
            'beneficios.*.imagen' => 'nullable|image|max:5120',
            'beneficios.*.eliminar_imagen' => 'nullable|boolean',
            'eliminar_imagen_empresarial' => 'nullable|boolean',
            'eliminar_portada_empresarial' => 'nullable|boolean',
            'eliminar_logo_header' => 'nullable|boolean',
            'eliminar_logo_footer' => 'nullable|boolean',
            'eliminar_favicon' => 'nullable|boolean',
            'ventajas' => 'nullable|array|max:4',
            'ventajas.*.icono' => 'nullable|string|max:100',
            'ventajas.*.titulo' => 'nullable|string|max:150',
            'ventajas.*.descripcion' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $logoHeader = $this->subirImagenEmpresa($request, 'logo_header', null);

            $logoFooter = $this->subirImagenEmpresa($request, 'logo_footer', null);

            $favicon = $this->subirImagenEmpresa($request, 'favicon', null);

            Empresa::create([
                'nombre' => $request->nombre,
                'nombre_comercial' => $request->nombre_comercial,
                'ruc' => $request->ruc,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'direccion' => $request->direccion,
                'descripcion' => $request->descripcion,

                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'whatsapp' => $request->whatsapp,
                'tiktok' => $request->tiktok,
                'linkedin' => $request->linkedin,

                // NOSOTROS
                'descripcion_empresarial' => $request->descripcion_empresarial,
                'mision_empresarial' => $request->mision_empresarial,
                'vision_empresarial' => $request->vision_empresarial,
'valores_empresariales' => $request->valores_empresariales,
                'empresa_indicadores' => json_encode($this->indicadoresFromRequest($request), JSON_UNESCAPED_UNICODE),
                'empresa_ventajas' => json_encode($this->ventajasFromRequest($request), JSON_UNESCAPED_UNICODE),
                'empresa_beneficios' => json_encode($this->beneficiosFromRequest($request), JSON_UNESCAPED_UNICODE),

                // IMAGENES EMPRESARIALES
                'imagen_empresarial' => $this->subirImagenEmpresa($request, 'imagen_empresarial', null),

                'portada_empresarial' => $this->subirImagenEmpresa($request, 'portada_empresarial', null),

                'logo_header' => $logoHeader,
                'logo_footer' => $logoFooter,
                'favicon' => $favicon,

                'estado' => 1
            ]);

            DB::commit();

            return back()->with('success', 'Empresa registrada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'beneficios' => 'nullable|array|max:4',
            'beneficios.*.titulo' => 'nullable|string|max:150',
            'beneficios.*.descripcion' => 'nullable|string|max:1000',
            'beneficios.*.enlace' => 'nullable|string|max:2048',
            'beneficios.*.texto_boton' => 'nullable|string|max:80',
            'beneficios.*.imagen' => 'nullable|image|max:5120',
            'beneficios.*.eliminar_imagen' => 'nullable|boolean',
            'eliminar_imagen_empresarial' => 'nullable|boolean',
            'eliminar_portada_empresarial' => 'nullable|boolean',
            'eliminar_logo_header' => 'nullable|boolean',
            'eliminar_logo_footer' => 'nullable|boolean',
            'eliminar_favicon' => 'nullable|boolean',
            'ventajas' => 'nullable|array|max:4',
            'ventajas.*.icono' => 'nullable|string|max:100',
            'ventajas.*.titulo' => 'nullable|string|max:150',
            'ventajas.*.descripcion' => 'nullable|string|max:1000',
        ]);

        $transaccionConfirmada = false;

        try {
            DB::beginTransaction();

            $imagenesParaEliminar = [];
            $empresa = Empresa::findOrFail($id);

            $logoHeader = $this->subirImagenEmpresa($request, 'logo_header', $empresa->logo_header, $imagenesParaEliminar);

            $logoFooter = $this->subirImagenEmpresa($request, 'logo_footer', $empresa->logo_footer, $imagenesParaEliminar);

            $favicon = $this->subirImagenEmpresa($request, 'favicon', $empresa->favicon, $imagenesParaEliminar);

            $imagenEmp = $this->subirImagenEmpresa($request, 'imagen_empresarial', $empresa->imagen_empresarial, $imagenesParaEliminar);

            $portadaEmp = $this->subirImagenEmpresa($request, 'portada_empresarial', $empresa->portada_empresarial, $imagenesParaEliminar);

            $empresa->update([
                'nombre' => $request->nombre,
                'nombre_comercial' => $request->nombre_comercial,
                'ruc' => $request->ruc,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'direccion' => $request->direccion,
                'descripcion' => $request->descripcion,

                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'whatsapp' => $request->whatsapp,
                'tiktok' => $request->tiktok,
                'linkedin' => $request->linkedin,

                // NOSOTROS
                'descripcion_empresarial' => $request->descripcion_empresarial,
                'mision_empresarial' => $request->mision_empresarial,
                'vision_empresarial' => $request->vision_empresarial,
'valores_empresariales' => $request->valores_empresariales,
                'empresa_indicadores' => json_encode($this->indicadoresFromRequest($request), JSON_UNESCAPED_UNICODE),
                'empresa_ventajas' => json_encode($this->ventajasFromRequest($request), JSON_UNESCAPED_UNICODE),
                'empresa_beneficios' => json_encode($this->beneficiosFromRequest($request, $empresa, $imagenesParaEliminar), JSON_UNESCAPED_UNICODE),

                // IMAGENES
                'imagen_empresarial' => $imagenEmp,
                'portada_empresarial' => $portadaEmp,

                'logo_header' => $logoHeader,
                'logo_footer' => $logoFooter,
                'favicon' => $favicon,
            ]);

            DB::commit();
            $transaccionConfirmada = true;

            foreach (array_unique($imagenesParaEliminar) as $imagen) {
                $this->eliminarImagenEmpresa($imagen, $empresa);
            }

            return back()->with('success', 'Empresa actualizada correctamente');

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            $mensaje = $transaccionConfirmada
                ? 'La empresa se actualizó, pero no se pudo eliminar una imagen anterior: ' . $e->getMessage()
                : $e->getMessage();

            return back()->with('error', $mensaje);
        }
    }

/**
     * Procesa la subida de una imagen de empresa.
     * Devuelve la ruta guardada, mantiene la anterior si no se subió archivo,
     * y lanza un error claro si el archivo se rechazó (tamaño/formato).
     */
    private function subirImagenEmpresa(Request $request, string $campo, ?string $actual, array &$imagenesParaEliminar = []): ?string
    {
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

        $file = $request->file($campo);

        if (!$file->isValid()) {
            $error = match ($file->getError()) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'supera el límite de tamaño permitido por el servidor',
                UPLOAD_ERR_PARTIAL => 'se subió de forma incompleta',
                UPLOAD_ERR_EXTENSION => 'fue bloqueado por una extensión del servidor',
                default => 'no se pudo procesar'
            };

            throw new \Exception("No se pudo guardar \"{$campo}\": el archivo {$error}. Reintenta con una imagen más pequeña o contacta al administrador.");
        }

        return uploadImageOptimized($file, 'empresa');
    }

    private function ventajasFromRequest(Request $request): array
    {
        return collect($request->input('ventajas', []))
            ->take(4)
            ->map(function ($ventaja) {
                return [
                    'icono' => $ventaja['icono'] ?? '',
                    'titulo' => $ventaja['titulo'] ?? '',
                    'descripcion' => $ventaja['descripcion'] ?? '',
                ];
            })
            ->values()
            ->all();
    }

    private function indicadoresFromRequest(Request $request): array
    {
        return collect($request->input('indicadores', []))
            ->map(function ($indicador) {
                return [
                    'valor' => $indicador['valor'] ?? '',
                    'titulo' => $indicador['titulo'] ?? '',
                ];
            })
            ->values()
            ->all();
    }

    private function beneficiosFromRequest(Request $request, ?Empresa $empresa = null, array &$imagenesParaEliminar = []): array
    {
        $beneficiosAnteriores = $empresa?->empresa_beneficios ?? [];

        return collect($request->input('beneficios', []))
            ->take(4)
            ->map(function ($beneficio, $index) use ($request, $beneficiosAnteriores, &$imagenesParaEliminar) {
                $imagen = $beneficiosAnteriores[$index]['imagen'] ?? null;
                $archivo = $request->file("beneficios.{$index}.imagen");

                if ($archivo) {
                    if ($imagen) {
                        $imagenesParaEliminar[] = $imagen;
                    }
                    $imagen = uploadImageOptimized($archivo, 'empresa');
                } elseif (!empty($beneficio['eliminar_imagen']) && $imagen) {
                    $imagenesParaEliminar[] = $imagen;
                    $imagen = null;
                }

                return [
                    'titulo' => trim($beneficio['titulo'] ?? ''),
                    'descripcion' => trim($beneficio['descripcion'] ?? ''),
                    'imagen' => $imagen,
                    'texto_boton' => trim($beneficio['texto_boton'] ?? ''),
                    'enlace' => trim($beneficio['enlace'] ?? ''),
                ];
            })
            ->filter(fn ($beneficio) => $beneficio['titulo'] !== ''
                || $beneficio['descripcion'] !== ''
                || $beneficio['imagen']
                || $beneficio['enlace'] !== '')
            ->values()
            ->all();
    }

    private function eliminarImagenEmpresa(string $ruta, Empresa $empresa): void
    {
        $imagenesActuales = [
            $empresa->imagen_empresarial,
            $empresa->portada_empresarial,
            $empresa->logo_header,
            $empresa->logo_footer,
            $empresa->favicon,
        ];

        foreach ($empresa->empresa_beneficios ?? [] as $beneficio) {
            $imagenesActuales[] = $beneficio['imagen'] ?? null;
        }

        if (in_array($ruta, $imagenesActuales, true)) {
            return;
        }

        $rutaPublica = realpath(public_path($ruta));
        $directorioPublico = realpath(public_path());

        if ($rutaPublica && $directorioPublico
            && str_starts_with($rutaPublica, $directorioPublico . DIRECTORY_SEPARATOR)
            && is_file($rutaPublica)) {
            if (!unlink($rutaPublica)) {
                throw new \RuntimeException("No se pudo eliminar el archivo \"{$ruta}\".");
            }
        }
    }
}
