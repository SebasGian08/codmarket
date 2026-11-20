<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\DocumentoTipo;
use App\Models\Reclamacion;
use App\Models\ReclamacionTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReclamacionController extends Controller
{
    public function index()
    {
        return view('pages.reclamaciones.index', [
            'documentosTipos' => DocumentoTipo::where('estado', 1)->orderBy('id_documento_tipo')->get(),
            'reclamacionesTipos' => ReclamacionTipo::where('estado', 1)->orderBy('id_reclamacion_tipo')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_documento_tipo' => 'required|exists:documentos_tipo,id_documento_tipo',
            'numero_documento' => 'required|string|max:20',
            'correo_electronico' => 'required|email|max:150',
            'numero_celular' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'tipo_bien' => 'required|in:PRODUCTO,SERVICIO',
            'monto_reclamado' => 'required|numeric|min:0|max:9999999999.99',
            'detalle_bien' => 'required|string|max:10000',
            'id_reclamacion_tipo' => 'required|exists:reclamaciones_tipo,id_reclamacion_tipo',
            'detalle_reclamacion' => 'required|string|max:10000',
            'pedido_consumidor' => 'required|string|max:10000',
            'acepta_datos' => 'required|accepted',
        ]);

        if ($request->filled('website')) {
            return back()->withErrors(['error' => 'No se pudo procesar el formulario.'])->withInput();
        }

        if (config('services.turnstile.site_key')) {
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => config('services.turnstile.secret_key'),
                    'response' => $request->input('cf-turnstile-response'),
                    'remoteip' => $request->ip(),
                ])
                ->json();

            if (!($response['success'] ?? false)) {
                return back()->withErrors(['error' => 'No se pudo completar la verificación anti-robot.'])->withInput();
            }
        }

        Reclamacion::create([
            'id_documento_tipo' => $validated['id_documento_tipo'],
            'numero_documento' => $validated['numero_documento'],
            'correo_electronico' => $validated['correo_electronico'],
            'numero_celular' => $validated['numero_celular'],
            'tipo_bien' => $validated['tipo_bien'],
            'monto_reclamado' => $validated['monto_reclamado'],
            'detalle_bien' => $validated['detalle_bien'],
            'id_reclamacion_tipo' => $validated['id_reclamacion_tipo'],
            'detalle_reclamacion' => $validated['detalle_reclamacion'],
            'pedido_consumidor' => $validated['pedido_consumidor'],
            'acepta_datos' => 1,
            'estado' => 1,
        ]);

        return redirect()->route('reclamaciones.index')->with('success', 'Tu reclamación fue registrada correctamente.');
    }
}
