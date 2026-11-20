<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReclamacionTipo extends Model
{
    protected $table = 'reclamaciones_tipo';
    protected $primaryKey = 'id_reclamacion_tipo';

    protected $fillable = [
        'nombre',
        'codigo',
        'estado',
    ];
}
