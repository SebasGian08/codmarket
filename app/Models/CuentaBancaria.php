<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaBancaria extends Model
{
    protected $table = 'cuentas_bancarias';
    protected $primaryKey = 'id_cuenta_bancaria';

    protected $fillable = [
        'nombre',
        'tipo',
        'moneda',
        'saldo',
        'estado'
    ];

    public function ventaPagos()
    {
        return $this->hasMany(VentaPago::class, 'id_cuenta_bancaria');
    }
}
