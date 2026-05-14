<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerPrincipal extends Model
{
    protected $table = 'banners_principales';

    protected $primaryKey = 'id_banner';

    protected $fillable = [
        'titulo',
        'subtitulo',
        'descripcion',
        'imagen',
        'imagen_mobile',
        'imagen_referencial',
        'enlace',
        'texto_boton',
        'orden',
        'estado',
        'solo_imagen',
        'fecha_inicio',
        'fecha_fin'
    ];
}