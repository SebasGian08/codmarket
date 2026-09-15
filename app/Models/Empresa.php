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
        'indicador_1_valor',
        'indicador_1_titulo',
        'indicador_2_valor',
        'indicador_2_titulo',
        'indicador_3_valor',
        'indicador_3_titulo',
        'indicador_4_valor',
        'indicador_4_titulo',
        'empresa_ventajas',
    ];

    protected $casts = [
        'empresa_ventajas' => 'array',
    ];
}
