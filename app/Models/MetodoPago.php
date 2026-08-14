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
        'estado'
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_metodo_pago');
    }
}
