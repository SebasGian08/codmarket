<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    protected $table = 'ventas_detalle';
    protected $primaryKey = 'id_venta_detalle';

    protected $fillable = [
        'id_venta',
        'id_variante',
        'cantidad',
        'precio',
        'subtotal'
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    public function variante()
    {
        return $this->belongsTo(ProductoVariante::class, 'id_variante');
    }
}
