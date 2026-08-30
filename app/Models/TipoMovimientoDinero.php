<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMovimientoDinero extends Model
{
    protected $table = 'tipos_movimiento_dinero';
    protected $primaryKey = 'id_tipo_movimiento_dinero';

    protected $fillable = [
        'codigo',
        'nombre',
        'signo',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function movimientos()
    {
        return $this->hasMany(MovimientoDinero::class, 'id_tipo_movimiento_dinero');
    }
}
