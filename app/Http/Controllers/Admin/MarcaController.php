<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::orderBy('orden', 'asc')->get();
        return view('admin.marcas.index', compact('marcas'));
    }

    private function uploadImage($file, $folder = 'marcas')
    {
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $path = public_path("uploads/$folder");

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file->move($path, $fileName);

        return "uploads/$folder/$fileName";
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:150',
            'logo' => 'nullable|image',
            'banner' => 'nullable|image'
        ]);

        DB::beginTransaction();

        try {

            $logo = $request->hasFile('logo')
                ? $this->uploadImage($request->file('logo'))
                : null;

            $banner = $request->hasFile('banner')
                ? $this->uploadImage($request->file('banner'))
                : null;

            Marca::create([
                'nombre' => $request->nombre,
                'slug' => Str::slug($request->nombre),
                'descripcion' => $request->descripcion,
                'logo' => $logo,
                'banner' => $banner,
                'sitio_web' => $request->sitio_web,
                'orden' => $request->orden ?? 0,
                'estado' => 1
            ]);

            DB::commit();

            return redirect()->route('admin.marcas.index')
                ->with('success', 'Marca creada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $marca = Marca::findOrFail($id);

        DB::beginTransaction();

        try {

            $logo = $marca->logo;
            $banner = $marca->banner;

            if ($request->hasFile('logo')) {
                $logo = $this->uploadImage($request->file('logo'));
            }

            if ($request->hasFile('banner')) {
                $banner = $this->uploadImage($request->file('banner'));
            }

            $marca->update([
                'nombre' => $request->nombre,
                'slug' => Str::slug($request->nombre),
                'descripcion' => $request->descripcion,
                'logo' => $logo,
                'banner' => $banner,
                'sitio_web' => $request->sitio_web,
                'orden' => $request->orden ?? 0,
                'estado' => $request->estado ?? 1
            ]);

            DB::commit();

            return redirect()->route('admin.marcas.index')
                ->with('success', 'Marca actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        Marca::findOrFail($id)->delete();

        return back()->with('delete', 'Marca eliminada correctamente');
    }
}