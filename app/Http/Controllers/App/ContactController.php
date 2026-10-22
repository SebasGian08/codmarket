<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\DocumentoTipo;
use App\Models\Service;
use App\Models\ContactSource;
use App\Models\Priority;
use App\Models\ContactStatus;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contacto.show', [
            'services' => Service::where('estado', 1)->get(),
            'marcas' => Marca::where('estado', 1)->orderBy('orden')->get(),
            'sources' => ContactSource::where('estado', 1)->get(),
            'priorities' => Priority::all(),
        ]);
    }

    public function store(Request $request)
    {
        $tipoConsulta = $request->input('tipo_consulta', 'servicio');

        $request->validate([
            'id_documento_tipo' => 'required|exists:documentos_tipo,id_documento_tipo',
            'email' => 'required|email|max:120',
            'telefono' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
            'tipo_consulta' => 'nullable|in:servicio,marca',
            'servicio' => [
                'nullable',
                'exists:services,id_service',
                Rule::requiredIf($tipoConsulta === 'servicio'),
            ],
            'id_marca' => [
                'nullable',
                'exists:marcas,id_marca',
                Rule::requiredIf($tipoConsulta === 'marca'),
            ],
            'cf-turnstile-response' => 'required'
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
            ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => config('services.turnstile.secret_key'),
                'response' => $request->input('cf-turnstile-response'),
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
            'id_service' => $tipoConsulta === 'servicio' ? $request->servicio : null,
            'id_marca' => $tipoConsulta === 'marca' ? $request->id_marca : null,
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
