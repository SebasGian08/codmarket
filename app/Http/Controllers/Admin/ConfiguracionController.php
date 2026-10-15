<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use App\Models\FooterLogo;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $configs = Configuracion::orderBy('categoria')
            ->orderBy('orden')
            ->get()
            ->groupBy('categoria');

        $footerLogos = \Schema::hasTable('footer_logos')
            ? FooterLogo::orderBy('orden', 'asc')->get()
            : collect();

        return view('admin.configuracion.index', compact('configs', 'footerLogos'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $clave => $valor) {

            if ($request->hasFile($clave)) {

                $valor = uploadImageOptimized($request->file($clave), 'config');
            }

            Configuracion::updateOrCreate(
                ['clave' => $clave],
                ['valor' => $valor]
            );
        }

        return back()->with('success', 'Configuración actualizada correctamente');
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria' => 'required',
            'clave' => 'required|unique:configuraciones,clave',
            'tipo' => 'required'
        ]);

        Configuracion::create([
            'categoria'   => $request->categoria,
            'clave'       => $request->clave,
            'valor'       => $request->valor ?? '',
            'descripcion' => $request->descripcion,
            'tipo'        => $request->tipo,
            'opciones'    => $request->opciones,
            'orden'       => $request->orden ?? 0,
        ]);

        return back()->with('success', 'Configuración creada correctamente');
    }
}