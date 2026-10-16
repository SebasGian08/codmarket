<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Contact;
use App\Models\Service;
use App\Models\ContactStatus;
use App\Models\Priority;
use App\Models\ContactSource;
use App\Models\ContactSeguimiento;
use App\Models\SeguimientoTipo;

class ContactController extends Controller
{

    public function index()
    {
        $statuses = ContactStatus::orderBy('id_status')->get();

        $contacts = Contact::with([
            'service',
            'status',
            'priority',
            'source',
            'documentoTipo',
            'seguimientos.tipo',
            'seguimientos.user'
        ])
        ->get()
        ->groupBy('id_status');

        $tipos = SeguimientoTipo::all();
        $sources = ContactSource::where('estado', 1)->get();
        $services = Service::where('estado', 1)->get();

        return view('admin.contacts.index', compact(
            'contacts',
            'statuses',
            'tipos',
            'sources',
            'services'
        ));
    }

    public function storeSeguimiento(Request $request, $id)
    {
        $request->validate([
            'tipo_id' => 'required',
            'comentario' => 'required'
        ]);

        ContactSeguimiento::create([
            'contact_id' => $id,
            'tipo_id' => $request->tipo_id,
            'comentario' => $request->comentario,
            'user_id' => Auth::id()
        ]);

        $contact = Contact::findOrFail($id);

        if ($contact->id_status == 1) {
            $contact->id_status = 2;
        } elseif ($contact->id_status == 2) {
            $contact->id_status = 3;
        }

        $contact->save();

        return back();
    }

    public function changeStatus(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $contact->id_status = $request->id_status;

        $contact->save();

        return back();
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $contact->update([
            'id_status' => $request->id_status,
            'id_priority' => $request->id_priority
        ]);

        return back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:200',
            'apellidos' => 'nullable|string|max:200',
            'id_documento_tipo' => 'nullable|exists:documentos_tipo,id_documento_tipo',
            'documento_numero' => 'nullable|string|max:20',
            'razon_social' => 'nullable|string|max:200',
            'email' => 'nullable|email|max:200',
            'telefono' => 'nullable|string|max:20',
            'service_id' => 'nullable|exists:services,id_service',
            'source_id' => 'nullable|exists:contact_sources,id_source',
            'mensaje' => 'nullable|string',
        ]);

        Contact::create([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos ?? '',
            'id_documento_tipo' => $request->id_documento_tipo,
            'razon_social' => $request->razon_social,
            'documento_numero' => $request->documento_numero,
            'email' => $request->email ?? '',
            'telefono' => $request->telefono ?? '',
            'id_service' => $request->service_id,
            'id_source' => $request->source_id,
            'mensaje' => $request->mensaje ?? '',
            'id_status' => 1,
            'id_priority' => 2,
        ]);

        return back();
    }

}