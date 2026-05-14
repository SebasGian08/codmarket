<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $table = 'promociones';

    protected $primaryKey = 'id_promocion';

    protected $fillable = [
        'titulo',
        'subtitulo',
        'descripcion',
        'imagen',
        'imagen_mobile',
        'enlace',
        'texto_boton',
        'color_texto',
        'orden',
        'estado',
        'fecha_inicio',
        'fecha_fin'
    ];
}