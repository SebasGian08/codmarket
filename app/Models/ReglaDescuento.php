<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReglaDescuento extends Model
{
    protected $table = 'reglas_descuento';
    protected $primaryKey = 'id_regla_descuento';

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_tipo_descuento',
        'valor',
        'cantidad_min',
        'cantidad_max',
        'id_tipo_venta',
        'estado',
    ];

    public function tipoVenta()
    {
        return $this->belongsTo(TipoVenta::class, 'id_tipo_venta');
    }

    public function tipoDescuento()
    {
        return $this->belongsTo(TipoDescuento::class, 'id_tipo_descuento');
    }
}
