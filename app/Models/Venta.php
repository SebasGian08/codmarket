<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'id_venta';

    protected $fillable = [
        'numero',
        'correlativo',
        'id_caja',
        'id_tienda',
        'id_usuario',
        'id_cliente',
        'nombre_cliente',
        'id_metodo_pago',
        'id_vendedor',
        'id_tipo_venta',
        'subtotal',
        'total',
        'subtotal_bruto',
        'descuento_items_total',
        'descuento_global',
        'id_motivo_descuento_global',
        'total_neto',
        'monto_recibido',
        'estado',
        'estado_cobro',
        'fecha_cierre',
        'usuario_cierre'
    ];

    protected $casts = [
        'fecha_cierre' => 'datetime',
    ];

    public function caja()
    {
        return $this->belongsTo(Caja::class, 'id_caja');
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'id_tienda');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago');
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'id_vendedor');
    }

    public function tipoVenta()
    {
        return $this->belongsTo(TipoVenta::class, 'id_tipo_venta');
    }

    public function motivoDescuentoGlobal()
    {
        return $this->belongsTo(MotivoDescuento::class, 'id_motivo_descuento_global');
    }

    public function detalle()
    {
        return $this->hasMany(VentaDetalle::class, 'id_venta');
    }

    public function ventaPagos()
    {
        return $this->hasMany(VentaPago::class, 'id_venta');
    }

    public function usuarioCierre()
    {
        return $this->belongsTo(Usuario::class, 'usuario_cierre');
    }

    public function getTotalPagadoAttribute()
    {
        return $this->ventaPagos()->sum('monto');
    }
}
