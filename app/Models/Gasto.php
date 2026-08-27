<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $table = 'gastos';
    protected $primaryKey = 'id_gasto';

    protected $fillable = [
        'numero',
        'correlativo',
        'id_tipo_gasto',
        'id_tienda',
        'id_caja',
        'id_cuenta_bancaria',
        'id_usuario',
        'fecha',
        'descripcion',
        'monto',
        'moneda',
        'observacion',
        'estado'
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function tipoGasto()
    {
        return $this->belongsTo(TipoGasto::class, 'id_tipo_gasto');
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'id_tienda');
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class, 'id_caja');
    }

    public function cuentaBancaria()
    {
        return $this->belongsTo(CuentaBancaria::class, 'id_cuenta_bancaria');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
