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
        'subtotal',
        'id_motivo_descuento',
        'id_tipo_descuento',
        'valor_descuento_unitario',
        'descuento_total_item',
        'subtotal_final'
    ];

    protected $appends = ['tipo_descuento'];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    public function variante()
    {
        return $this->belongsTo(ProductoVariante::class, 'id_variante');
    }

    public function motivoDescuento()
    {
        return $this->belongsTo(MotivoDescuento::class, 'id_motivo_descuento');
    }

    public function tipoDescuento()
    {
        return $this->belongsTo(TipoDescuento::class, 'id_tipo_descuento');
    }

    public function getTipoDescuentoAttribute()
    {
        return optional($this->tipoDescuento()->first())->codigo;
    }
}
