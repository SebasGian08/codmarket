<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FooterLogoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|max:4096',
            'titulo' => 'nullable|string|max:150',
            'url' => 'nullable|string|max:255',
            'orden' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $imagen = uploadImageOptimized($request->file('imagen'), 'footer-logos', 400, 90, 200);

            FooterLogo::create([
                'titulo' => $request->titulo,
                'imagen' => $imagen,
                'url' => $request->url,
                'orden' => $request->orden ?? 0,
                'estado' => 1,
            ]);

            DB::commit();

            return back()->with('success', 'Logo agregado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'imagen' => 'nullable|image|max:4096',
            'titulo' => 'nullable|string|max:150',
            'url' => 'nullable|string|max:255',
            'orden' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $logo = FooterLogo::findOrFail($id);

            $data = [
                'titulo' => $request->titulo,
                'url' => $request->url,
                'orden' => $request->orden ?? 0,
            ];

            if ($request->hasFile('imagen')) {
                $data['imagen'] = uploadImageOptimized($request->file('imagen'), 'footer-logos', 400, 90, 200);
            }

            $logo->update($data);

            DB::commit();

            return back()->with('success', 'Logo actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $logo = FooterLogo::findOrFail($id);
        $logo->delete();

        return back()->with('success', 'Logo eliminado');
    }
}
