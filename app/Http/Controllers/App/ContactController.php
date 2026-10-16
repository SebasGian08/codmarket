<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\DocumentoTipo;
use App\Models\Service;
use App\Models\ContactSource;
use App\Models\Priority;
use App\Models\ContactStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contacto.show', [
            'services' => Service::where('estado', 1)->get(),
            'sources' => ContactSource::where('estado', 1)->get(),
            'priorities' => Priority::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_documento_tipo' => 'required|exists:documentos_tipo,id_documento_tipo',
            'email' => 'required|email',
            'telefono' => 'required',
            'message' => 'required',
            'servicio' => 'required|exists:services,id_service',
            'g-recaptcha-response' => 'required'
        ]);

        $documentoTipo = DocumentoTipo::find($request->id_documento_tipo);
        $esEmpresa = $documentoTipo && strtoupper((string) $documentoTipo->codigo) === 'RUC';

        if ($esEmpresa) {
            $request->validate([
                'razon_social' => 'required|string|max:200',
                'documento_numero' => 'required|digits_between:6,11',
            ]);

            $nombres = $request->razon_social;
            $apellidos = '';
            $razonSocial = $request->razon_social;
            $documentoNumero = $request->documento_numero;
        } else {
            $request->validate([
                'nombre' => 'required|string|max:100',
                'apellidos' => 'required|string|max:100',
            ]);

            $nombres = $request->nombre;
            $apellidos = $request->apellidos;
            $razonSocial = null;
            $documentoNumero = null;
        }

        if ($request->website != null) {
            return back()->withErrors(['error' => 'Bot detectado']);
        }

        $response = Http::asForm()
            ->timeout(10)
            ->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ])
            ->json();

        if (!($response['success'] ?? false)) {
            return back()->withErrors(['error' => 'Verificación anti-robot fallida']);
        }

        Contact::create([
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'id_documento_tipo' => $request->id_documento_tipo,
            'razon_social' => $razonSocial,
            'documento_numero' => $documentoNumero,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'id_service' => $request->servicio,
            'id_source' => 1,
            'id_status' => 1,
            'id_priority' => 2,
            'mensaje' => $request->message,
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->back()->with('success', 'Mensaje enviado correctamente');
    }
}
