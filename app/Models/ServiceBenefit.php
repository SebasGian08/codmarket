<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceBenefit extends Model
{
    protected $table = 'service_benefits';

    protected $fillable = [
        'service_id',
        'titulo',
        'descripcion',
        'icono'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id_service');
    }
}