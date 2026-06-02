<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrabajoRealizado extends Model
{
    protected $table = 'trabajos_realizados';

    protected $primaryKey = 'id_trabajos_realizados';

    public $timestamps = true;

    protected $fillable = [
        'titulo',
        'slug',
        'cliente',
        'descripcion',
        'imagen',
        'url',
        'orden',
        'estado'
    ];
}