<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaBancaria extends Model
{
    protected $table = 'cuentas_bancarias';
    protected $primaryKey = 'id_cuenta_bancaria';

    protected $fillable = [
        'nombre_banco',
        'tipo_cuenta',
        'numero_cuenta',
        'titular',
        'saldo_actual',
        'estado'
    ];

    public function tipoCuenta()
    {
        return $this->belongsTo(CuentaTipoCuenta::class, 'tipo_cuenta');
    }

    public function ventaPagos()
    {
        return $this->hasMany(VentaPago::class, 'id_cuenta_bancaria');
    }
}
