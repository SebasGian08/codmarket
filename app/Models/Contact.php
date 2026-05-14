<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;
    protected $table = 'contacts';
    protected $primaryKey = 'id_contact';

    protected $fillable = [
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'id_service',
        'id_source',
        'id_status',
        'id_priority',
        'mensaje',
        'ip',
        'user_agent'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    public function source()
    {
        return $this->belongsTo(ContactSource::class, 'id_source');
    }

    public function status()
    {
        return $this->belongsTo(ContactStatus::class, 'id_status');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'id_priority');
    }

    public function seguimientos()
    {
        return $this->hasMany(ContactSeguimiento::class, 'contact_id', 'id_contact')
                    ->orderBy('created_at', 'desc');
    }
}