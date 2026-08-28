<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoTipo extends Model
{
    protected $table = 'movimientos_tipo';
    protected $primaryKey = 'id_tipo_movimiento';

    protected $fillable = [
        'codigo',
        'nombre',
        'signo',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}
