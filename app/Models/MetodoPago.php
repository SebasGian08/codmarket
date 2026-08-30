<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table = 'metodos_pagos';
    protected $primaryKey = 'id_metodo_pago';

    protected $fillable = [
        'nombre',
        'codigo',
        'id_destino_pago',
        'estado'
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_metodo_pago');
    }

    public function destinoPago()
    {
        return $this->belongsTo(DestinoPago::class, 'id_destino_pago');
    }

    /**
     * ¿El método de pago afecta a la CAJA? (configurable vía maestro destinos_pago)
     */
    public function afectaCaja()
    {
        return optional($this->destinoPago)->codigo === 'caja';
    }

    /**
     * ¿El método de pago afecta a una CUENTA BANCARIA?
     */
    public function afectaCuenta()
    {
        return optional($this->destinoPago)->codigo === 'cuenta';
    }
}
