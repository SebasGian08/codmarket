<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoIngresoEconomico extends Model
{
    protected $table = 'tipos_ingresos_economicos';
    protected $primaryKey = 'id_tipo_ingreso_economico';

    protected $fillable = [
        'nombre',
        'estado'
    ];

    public function ingresos()
    {
        return $this->hasMany(IngresoEconomico::class, 'id_tipo_ingreso_economico');
    }
}
