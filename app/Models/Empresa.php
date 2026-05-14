<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    protected $primaryKey = 'id_empresa';

    protected $fillable = [
        'nombre',
        'nombre_comercial',
        'ruc',
        'telefono',
        'correo',
        'direccion',
        'logo_header',
        'logo_footer',
        'favicon',
        'descripcion',
        'facebook',
        'instagram',
        'whatsapp',
        'tiktok',
        'estado',
        // NOSOTROS
        'descripcion_empresarial',
        'mision_empresarial',
        'vision_empresarial',
        'valores_empresariales',
        'imagen_empresarial',
        'portada_empresarial',
    ];
}