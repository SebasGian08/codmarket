<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSeguimiento extends Model
{
    protected $table = 'contacts_seguimiento';

    protected $fillable = [
        'contact_id',
        'tipo_id',
        'comentario',
        'user_id'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'id_contact');
    }

    public function tipo()
    {
        return $this->belongsTo(SeguimientoTipo::class, 'tipo_id');
    }

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id_usuario');
    }
}