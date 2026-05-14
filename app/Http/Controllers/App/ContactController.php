<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Service;
use App\Models\ContactSource;
use App\Models\Priority;
use App\Models\ContactStatus;
use Illuminate\Http\Request;

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
            'nombre' => 'required',
            'apellidos' => 'required',
            'email' => 'required|email',
            'telefono' => 'required',
            'message' => 'required',
            'servicio' => 'required|exists:services,id_service',
            'g-recaptcha-response' => 'required'
        ]);

        if ($request->website != null) {
            return back()->withErrors(['error' => 'Bot detectado']);
        }

        $verify = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret=" . env('RECAPTCHA_SECRET_KEY') .
            "&response=" . $request->input('g-recaptcha-response')
        );

        $response = json_decode($verify);

        if (!$response->success) {
            return back()->withErrors(['error' => 'Verificación anti-robot fallida']);
        }

        Contact::create([
            'nombres' => $request->nombre,
            'apellidos' => $request->apellidos,
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