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

                // NOSOTROS
                'descripcion_empresarial' => $request->descripcion_empresarial,
                'mision_empresarial' => $request->mision_empresarial,
                'vision_empresarial' => $request->vision_empresarial,
'valores_empresariales' => $request->valores_empresariales,
                'empresa_indicadores' => json_encode($this->indicadoresFromRequest($request), JSON_UNESCAPED_UNICODE),
                'empresa_ventajas' => json_encode($this->ventajasFromRequest($request), JSON_UNESCAPED_UNICODE),

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
        try {
            DB::beginTransaction();

            $empresa = Empresa::findOrFail($id);

            $logoHeader = $this->subirImagenEmpresa($request, 'logo_header', $empresa->logo_header);

            $logoFooter = $this->subirImagenEmpresa($request, 'logo_footer', $empresa->logo_footer);

            $favicon = $this->subirImagenEmpresa($request, 'favicon', $empresa->favicon);

            $imagenEmp = $this->subirImagenEmpresa($request, 'imagen_empresarial', $empresa->imagen_empresarial);

            $portadaEmp = $this->subirImagenEmpresa($request, 'portada_empresarial', $empresa->portada_empresarial);

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

                // NOSOTROS
                'descripcion_empresarial' => $request->descripcion_empresarial,
                'mision_empresarial' => $request->mision_empresarial,
                'vision_empresarial' => $request->vision_empresarial,
'valores_empresariales' => $request->valores_empresariales,
                'empresa_indicadores' => json_encode($this->indicadoresFromRequest($request), JSON_UNESCAPED_UNICODE),
                'empresa_ventajas' => json_encode($this->ventajasFromRequest($request), JSON_UNESCAPED_UNICODE),

                // IMAGENES
                'imagen_empresarial' => $imagenEmp,
                'portada_empresarial' => $portadaEmp,

                'logo_header' => $logoHeader,
                'logo_footer' => $logoFooter,
                'favicon' => $favicon,
            ]);

            DB::commit();

            return back()->with('success', 'Empresa actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

/**
     * Procesa la subida de una imagen de empresa.
     * Devuelve la ruta guardada, mantiene la anterior si no se subió archivo,
     * y lanza un error claro si el archivo se rechazó (tamaño/formato).
     */
    private function subirImagenEmpresa(Request $request, string $campo, ?string $actual): ?string
    {
        if (!$request->hasFile($campo)) {
            return $actual;
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
}
