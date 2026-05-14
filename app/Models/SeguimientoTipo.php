<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientoTipo extends Model
{
    protected $table = 'seguimiento_tipos';

    protected $fillable = ['nombre', 'color', 'icono', 'estado'];
}