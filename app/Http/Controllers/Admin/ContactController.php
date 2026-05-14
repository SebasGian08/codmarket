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
        Contact::create([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'service_id' => $request->service_id,
            'source_id' => $request->source_id,
            'mensaje' => $request->mensaje,
            'id_status' => 1
        ]);

        return back();
    }

}