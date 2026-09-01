<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoVenta extends Model
{
    protected $table = 'tipos_venta';
    protected $primaryKey = 'id_tipo_venta';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function reglas()
    {
        return $this->hasMany(ReglaDescuento::class, 'id_tipo_venta');
    }
}
