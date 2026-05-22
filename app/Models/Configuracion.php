<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';
    protected $primaryKey = 'id_configuracion';

    protected $fillable = [
        'categoria',
        'clave',
        'valor',
        'descripcion',
        'tipo',
        'opciones',
        'orden'
    ];

    public $timestamps = false;
}