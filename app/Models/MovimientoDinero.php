<?php

namespace App\Models;

use App\Services\MovimientoDineroService;
use Illuminate\Database\Eloquent\Model;

class MovimientoDinero extends Model
{
    protected $table = 'movimientos_dinero';
    protected $primaryKey = 'id_movimiento_dinero';

    protected $fillable = [
        'id_tipo_movimiento_dinero',
        'id_caja',
        'id_cuenta_bancaria',
        'referencia_tipo',
        'id_referencia',
        'id_metodo_pago',
        'monto',
        'moneda',
        'fecha',
        'observacion',
        'id_usuario_registro',
        'estado'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'estado' => 'boolean',
    ];

    public function tipoMovimientoDinero()
    {
        return $this->belongsTo(TipoMovimientoDinero::class, 'id_tipo_movimiento_dinero');
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class, 'id_caja');
    }

    public function cuentaBancaria()
    {
        return $this->belongsTo(CuentaBancaria::class, 'id_cuenta_bancaria');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago');
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_registro');
    }

    /**
     * Resuelve la referencia polimórfica (convención centralizada de MovimientoDineroService).
     */
    public function referencia()
    {
        $tipo = $this->referencia_tipo;
        if (!$tipo) {
            return null;
        }

        switch ($tipo) {
            case MovimientoDineroService::REF_VENTA_PAGO:
                return $this->belongsTo(VentaPago::class, 'id_referencia');
            case MovimientoDineroService::REF_GASTO:
                return $this->belongsTo(Gasto::class, 'id_referencia');
            case MovimientoDineroService::REF_INGRESO_ECONOMICO:
                return $this->belongsTo(IngresoEconomico::class, 'id_referencia');
            case MovimientoDineroService::REF_TRANSFERENCIA_DINERO:
                return $this->belongsTo(TransferenciaDinero::class, 'id_referencia');
        }

        return null;
    }

    /**
     * Signo del flujo según el maestro de tipos.
     */
    public function getFlujoSignedAttribute()
    {
        $signo = optional($this->tipoMovimientoDinero)->signo;
        return ($signo === '-') ? -1 : 1;
    }

    /**
     * Valor firmado del movimiento (monto con signo de entrada/salida).
     */
    public function getMontoSignedAttribute()
    {
        return $this->monto * $this->flujoSigned;
    }
}
