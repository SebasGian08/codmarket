<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portafolio extends Model
{
    use SoftDeletes;

    protected $table = 'portafolios';
    
    protected $fillable = [
        'service_id',
        'titulo',
        'slug',
        'cliente',
        'categoria',
        'tipo',
        'descripcion',
        'imagen',
        'url_demo',
        'estado'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id_service');
    }
}