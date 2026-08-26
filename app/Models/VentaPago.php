<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaPago extends Model
{
    protected $table = 'venta_pagos';
    protected $primaryKey = 'id_venta_pago';

    protected $fillable = [
        'id_venta',
        'id_metodo_pago',
        'id_cuenta_bancaria',
        'monto',
        'moneda',
        'id_usuario_registro'
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago');
    }

    public function cuentaBancaria()
    {
        return $this->belongsTo(CuentaBancaria::class, 'id_cuenta_bancaria');
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_registro');
    }
}
