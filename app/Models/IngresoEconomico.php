<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngresoEconomico extends Model
{
    protected $table = 'ingresos_economicos';
    protected $primaryKey = 'id_ingreso_economico';

    protected $fillable = [
        'numero',
        'correlativo',
        'id_tipo_ingreso_economico',
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

    public function tipoIngresoEconomico()
    {
        return $this->belongsTo(TipoIngresoEconomico::class, 'id_tipo_ingreso_economico');
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
