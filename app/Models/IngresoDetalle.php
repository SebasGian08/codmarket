<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngresoDetalle extends Model
{
    protected $table = 'ingresos_detalle';
    protected $primaryKey = 'id_ingreso_detalle';

    protected $fillable = [
        'id_ingreso',
        'id_variante',
        'cantidad',
        'costo'
    ];

    public function ingreso()
    {
        return $this->belongsTo(Ingreso::class, 'id_ingreso');
    }

    public function variante()
    {
        return $this->belongsTo(ProductoVariante::class, 'id_variante');
    }
}
