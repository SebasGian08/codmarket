<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MarcaServiceInterface;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    protected $service;

    public function __construct(MarcaServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $marcas = $this->service->getAll();

        return view('admin.marcas.index', compact('marcas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:150',
            'logo' => 'nullable|image',
            'banner' => 'nullable|image'
        ]);

        try {
            $this->service->create($this->data($request));

            return redirect()->route('admin.marcas.index')
                ->with('success', 'Marca creada correctamente');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:150',
            'logo' => 'nullable|image',
            'banner' => 'nullable|image'
        ]);

        try {
            $this->service->update($id, $this->data($request));

            return redirect()->route('admin.marcas.index')
                ->with('success', 'Marca actualizada correctamente');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return back()->with('delete', 'Marca eliminada correctamente');
    }

    private function data(Request $request)
    {
        return [
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'logo' => $request->file('logo'),
            'banner' => $request->file('banner'),
            'sitio_web' => $request->input('sitio_web'),
            'orden' => $request->input('orden', 0),
            'estado' => $request->input('estado', 1),
        ];
    }
}
