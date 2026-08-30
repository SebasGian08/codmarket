<?php

namespace App\Models;

use App\Services\MovimientoDineroService;
use Illuminate\Database\Eloquent\Model;

class TransferenciaDinero extends Model
{
    protected $table = 'transferencias_dinero';
    protected $primaryKey = 'id_transferencia_dinero';

    protected $fillable = [
        'numero',
        'correlativo',
        'id_tienda',
        'id_caja_origen',
        'id_cuenta_origen',
        'id_caja_destino',
        'id_cuenta_destino',
        'id_usuario',
        'fecha',
        'monto',
        'moneda',
        'observacion',
        'estado'
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'id_tienda');
    }

    public function cajaOrigen()
    {
        return $this->belongsTo(Caja::class, 'id_caja_origen');
    }

    public function cuentaOrigen()
    {
        return $this->belongsTo(CuentaBancaria::class, 'id_cuenta_origen');
    }

    public function cajaDestino()
    {
        return $this->belongsTo(Caja::class, 'id_caja_destino');
    }

    public function cuentaDestino()
    {
        return $this->belongsTo(CuentaBancaria::class, 'id_cuenta_destino');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoDinero::class, 'id_referencia')
            ->where('referencia_tipo', MovimientoDineroService::REF_TRANSFERENCIA_DINERO);
    }
}
