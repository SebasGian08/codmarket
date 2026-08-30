<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaTipoCuenta extends Model
{
    protected $table = 'cuentas_tipo_cuenta';
    protected $primaryKey = 'id_tipo_cuenta';

    protected $fillable = [
        'nombre',
        'estado'
    ];

    public function cuentas()
    {
        return $this->hasMany(CuentaBancaria::class, 'tipo_cuenta');
    }
}
