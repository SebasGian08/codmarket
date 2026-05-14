<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portafolio;
use App\Models\Service;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PortafolioController extends Controller
{
    public function index()
    {
        $portafolios = Portafolio::with('service')
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        $services = Service::whereNull('deleted_at')->get();

        return view('admin.portafolios.index', compact('portafolios', 'services'));
    }

    private function uploadImage($file, $folder = 'portafolios')
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $destinationPath = base_path('uploads/' . $folder);
        $file->move($destinationPath, $fileName);
        return 'uploads/' . $folder . '/' . $fileName;
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'service_id' => 'required',
            'imagen' => 'nullable|image'
        ]);

        try {
            DB::beginTransaction();

            $imagen = null;

            if ($request->hasFile('imagen')) {
                $imagen = $this->uploadImage($request->file('imagen'));
            }

            Portafolio::create([
                'service_id' => $request->service_id,
                'titulo' => $request->titulo,
                'slug' => Str::slug($request->titulo),
                'cliente' => $request->cliente,
                'categoria' => $request->categoria,
                'tipo' => $request->tipo,
                'descripcion' => $request->descripcion,
                'imagen' => $imagen,
                'url_demo' => $request->url_demo,
                'estado' => 1
            ]);

            DB::commit();

            return back()->with('success', 'Portafolio creado');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $portafolio = Portafolio::findOrFail($id);

        $imagen = $portafolio->imagen;

        if ($request->hasFile('imagen')) {
            $imagen = $this->uploadImage($request->file('imagen'));
        }

        $portafolio->update([
            'service_id' => $request->service_id,
            'titulo' => $request->titulo,
            'slug' => Str::slug($request->titulo),
            'cliente' => $request->cliente,
            'categoria' => $request->categoria,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'imagen' => $imagen,
            'url_demo' => $request->url_demo,
        ]);

        return back()->with('success', 'Actualizado correctamente');
    }

    public function destroy($id)
    {
        Portafolio::findOrFail($id)->delete();
        return back()->with('delete', 'Eliminado');
    }
}