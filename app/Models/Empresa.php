<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'empresa_indicadores',
    ];

    /**
     * Normaliza un valor JSON/texto a array de forma segura.
     * Resiste cadenas vacías, JSON inválido o doble-codificado.
     */
    private function normalizarJson(mixed $valor): array
    {
        $data = $valor ?? [];

        while (!is_array($data) && is_string($data) && $data !== '') {
            $decoded = json_decode($data, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }

            $data = $decoded;
        }

        return is_array($data) ? array_values($data) : [];
    }

    protected function empresaVentajas(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->normalizarJson($value),
            set: fn ($value) => is_string($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE),
        );
    }

    protected function empresaIndicadores(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->normalizarJson($value),
            set: fn ($value) => is_string($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE),
        );
    }
}
