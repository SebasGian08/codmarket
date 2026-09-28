<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreguntaFrecuente extends Model
{
    protected $table = 'preguntas_frecuentes';
    protected $primaryKey = 'id_pregunta_frecuente';

    protected $fillable = [
        'pregunta',
        'respuesta',
        'orden',
        'estado',
    ];
}
