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

            $logoHeader = $request->file('logo_header')
                ? uploadImageOptimized($request->file('logo_header'), 'empresa')
                : null;

            $logoFooter = $request->file('logo_footer')
                ? uploadImageOptimized($request->file('logo_footer'), 'empresa')
                : null;

            $favicon = $request->file('favicon')
                ? uploadImageOptimized($request->file('favicon'), 'empresa')
                : null;

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

                // IMAGENES EMPRESARIALES (si las tienes como input file luego)
                'imagen_empresarial' => $request->imagen_empresarial
                    ? uploadImageOptimized($request->file('imagen_empresarial'), 'empresa')
                    : null,

                'portada_empresarial' => $request->portada_empresarial
                    ? uploadImageOptimized($request->file('portada_empresarial'), 'empresa')
                    : null,

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

            $logoHeader = $request->file('logo_header')
                ? uploadImageOptimized($request->file('logo_header'), 'empresa')
                : $empresa->logo_header;

            $logoFooter = $request->file('logo_footer')
                ? uploadImageOptimized($request->file('logo_footer'), 'empresa')
                : $empresa->logo_footer;

            $favicon = $request->file('favicon')
                ? uploadImageOptimized($request->file('favicon'), 'empresa')
                : $empresa->favicon;

            $imagenEmp = $request->file('imagen_empresarial')
                ? uploadImageOptimized($request->file('imagen_empresarial'), 'empresa')
                : $empresa->imagen_empresarial;

            $portadaEmp = $request->file('portada_empresarial')
                ? uploadImageOptimized($request->file('portada_empresarial'), 'empresa')
                : $empresa->portada_empresarial;

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
}