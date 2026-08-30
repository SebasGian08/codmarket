<?php

namespace App\Services;

use App\Models\CuentaBancaria;
use App\Models\MetodoPago;
use App\Models\MovimientoDinero;
use App\Models\Gasto;
use App\Models\IngresoEconomico;
use App\Models\TipoMovimientoDinero;
use App\Models\TransferenciaDinero;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

/**
 * Capa financiera del sistema (DINERO).
 *
 * Separa STOCK (InventarioService) de DINERO. Centraliza la creación de
 * movimientos financieros y la actualización de saldos de caja/cuentas,
 * apoyándose en los maestros (tipos_movimiento_dinero, destinos_pago).
 *
 * Los controladores NO actualizan saldos directamente: delegan en este servicio.
 * Se asume que el llamador ejecuta dentro de una transacción; si no hay una
 * activa, el servicio la inicia para garantizar atomicidad.
 */
class MovimientoDineroService
{
    // Convención centralizada (única) para referencia_tipo. NO usar texto libre.
    const REF_VENTA_PAGO = 'venta_pago';
    const REF_GASTO = 'gasto';
    const REF_INGRESO_ECONOMICO = 'ingreso_economico';
    const REF_TRANSFERENCIA_DINERO = 'transferencia_dinero';

    // Códigos de tipos de movimiento de dinero (maestro tipos_movimiento_dinero).
    const TIPO_SALDO_INICIAL = 'saldo_inicial';
    const TIPO_VENTA = 'venta';
    const TIPO_GASTO = 'gasto';
    const TIPO_INGRESO_ECONOMICO = 'ingreso_economico';
    const TIPO_TRANSFERENCIA_SALIDA = 'transferencia_salida';
    const TIPO_TRANSFERENCIA_ENTRADA = 'transferencia_entrada';
    const TIPO_ANULACION_VENTA = 'anulacion_venta';
    const TIPO_ANULACION_GASTO = 'anulacion_gasto';
    const TIPO_ANULACION_INGRESO_ECONOMICO = 'anulacion_ingreso_economico';
    const TIPO_ANULACION_TRANSFERENCIA_SALIDA = 'anulacion_transferencia_salida';
    const TIPO_ANULACION_TRANSFERENCIA_ENTRADA = 'anulacion_transferencia_entrada';

    // Mapa de reversa: tipo original -> tipo de reversión (signo opuesto).
    const REVERSAS = [
        self::TIPO_VENTA => self::TIPO_ANULACION_VENTA,
        self::TIPO_GASTO => self::TIPO_ANULACION_GASTO,
        self::TIPO_INGRESO_ECONOMICO => self::TIPO_ANULACION_INGRESO_ECONOMICO,
        self::TIPO_TRANSFERENCIA_ENTRADA => self::TIPO_ANULACION_TRANSFERENCIA_ENTRADA,
        self::TIPO_TRANSFERENCIA_SALIDA => self::TIPO_ANULACION_TRANSFERENCIA_SALIDA,
    ];

    /**
     * Reconoce si ya hay una transacción activa; si no, la inicia.
     */
    protected function ensureTransaction()
    {
        if (DB::transactionLevel() === 0) {
            DB::beginTransaction();
            return true;
        }
        return false;
    }

    /**
     * Devuelve el destino configurado de un método de pago desde el maestro.
     * Retorna ['caja' => id|null, 'cuenta' => id|null].
     */
    public function destinoDeMetodo($idMetodoPago, $idCaja = null, $idCuenta = null)
    {
        $metodo = $idMetodoPago instanceof MetodoPago
            ? $idMetodoPago
            : MetodoPago::with('destinoPago')->find($idMetodoPago);

        if (!$metodo || !$metodo->destinoPago) {
            throw new \Exception('El método de pago no tiene un destino financiero configurado');
        }

        if ($metodo->destinoPago->codigo === 'caja') {
            if (!$idCaja) {
                throw new \Exception('El método ' . $metodo->nombre . ' requiere una caja');
            }
            return ['caja' => $idCaja, 'cuenta' => null];
        }

        // destino = cuenta
        if (!$idCuenta) {
            throw new \Exception('El método ' . $metodo->nombre . ' requiere una cuenta bancaria');
        }
        return ['caja' => null, 'cuenta' => $idCuenta];
    }

    /**
     * Núcleo: registra un movimiento financiero y actualiza el saldo del destino.
     *
     * @param string $tipoCodigo  código del tipo (maestro tipos_movimiento_dinero)
     * @param float  $monto
     * @param string $moneda
     * @param int    $idCaja    destino caja (null si el destino es cuenta)
     * @param int    $idCuenta  destino cuenta (null si el destino es caja)
     */
    public function registrarMovimiento(
        $tipoCodigo,
        $monto,
        $moneda = 'PEN',
        $idCaja = null,
        $idCuenta = null,
        $referenciaTipo = null,
        $idReferencia = null,
        $idMetodoPago = null,
        $observacion = null,
        $fecha = null
    ) {
        $ownTxn = $this->ensureTransaction();

        try {
            $tipo = TipoMovimientoDinero::where('codigo', $tipoCodigo)->where('estado', 1)->first();
            if (!$tipo) {
                throw new \Exception('Tipo de movimiento de dinero inválido: ' . $tipoCodigo);
            }

            if ($monto <= 0) {
                throw new \Exception('El monto del movimiento debe ser mayor a 0');
            }

            // Exactamente un destino (caja XOR cuenta)
            if ($idCaja && $idCuenta) {
                throw new \Exception('Un movimiento de dinero no puede afectar caja y cuenta a la vez');
            }
            if (!$idCaja && !$idCuenta) {
                throw new \Exception('Debe indicarse un destino financiero (caja o cuenta)');
            }

            $signo = ($tipo->signo === '-') ? -1 : 1;

            // Actualizar saldo del destino
            if ($idCuenta) {
                $cuenta = CuentaBancaria::where('id_cuenta_bancaria', $idCuenta)->lockForUpdate()->first();
                if (!$cuenta) {
                    throw new \Exception('Cuenta bancaria no encontrada');
                }
                $cuenta->saldo_actual = (float) $cuenta->saldo_actual + ($signo * $monto);
                $cuenta->save();
            }

            $movimiento = MovimientoDinero::create([
                'id_tipo_movimiento_dinero' => $tipo->id_tipo_movimiento_dinero,
                'id_caja' => $idCaja,
                'id_cuenta_bancaria' => $idCuenta,
                'referencia_tipo' => $referenciaTipo,
                'id_referencia' => $idReferencia,
                'id_metodo_pago' => $idMetodoPago,
                'monto' => $monto,
                'moneda' => $moneda,
                'fecha' => $fecha ?: now(),
                'observacion' => $observacion,
                'id_usuario_registro' => auth()->id(),
                'estado' => 1,
            ]);

            if ($ownTxn) {
                DB::commit();
            }

            return $movimiento;
        } catch (\Exception $e) {
            if ($ownTxn) {
                DB::rollBack();
            }
            throw $e;
        }
    }

    /**
     * Aplica el cobro de una venta (todos sus pagos) a caja/cuenta.
     * Debe ejecutarse dentro de la transacción del cierre de venta.
     */
    public function aplicarCobroVenta($idVenta, $idCaja, $pagos)
    {
        foreach ($pagos as $pago) {
            $this->aplicarVentaPago($idVenta, $idCaja, $pago);
        }
    }

    /**
     * Aplica un único pago de venta a su destino según el método de pago.
     * $pago debe incluir id_metodo_pago, id_cuenta_bancaria y monto.
     */
    public function aplicarVentaPago($idVenta, $idCaja, $pago)
    {
        $idMetodo = $pago['id_metodo_pago'] ?? null;
        $idCuenta = $pago['id_cuenta_bancaria'] ?? null;
        $monto = (float) ($pago['monto'] ?? 0);

        $metodo = MetodoPago::with('destinoPago')->find($idMetodo);
        if (!$metodo) {
            throw new \Exception('Debe seleccionar un método de pago válido');
        }

        $destino = $this->destinoDeMetodo($metodo, $idCaja, $idCuenta);

        return $this->registrarMovimiento(
            self::TIPO_VENTA,
            $monto,
            $pago['moneda'] ?? 'PEN',
            $destino['caja'],
            $destino['cuenta'],
            self::REF_VENTA_PAGO,
            $idVenta,
            $idMetodo,
            'Cobro de venta',
            now()
        );
    }

    /**
     * Aplica un gasto (egreso) a caja o cuenta según id_caja XOR id_cuenta.
     */
    public function aplicarGasto(Gasto $gasto)
    {
        return $this->registrarMovimiento(
            self::TIPO_GASTO,
            (float) $gasto->monto,
            $gasto->moneda ?: 'PEN',
            $gasto->id_caja,
            $gasto->id_cuenta_bancaria,
            self::REF_GASTO,
            $gasto->id_gasto,
            null,
            'Gasto ' . $gasto->numero . ': ' . $gasto->descripcion,
            $gasto->fecha
        );
    }

    /**
     * Aplica un ingreso económico (entrada) a caja o cuenta.
     */
    public function aplicarIngresoEconomico(IngresoEconomico $ingreso)
    {
        return $this->registrarMovimiento(
            self::TIPO_INGRESO_ECONOMICO,
            (float) $ingreso->monto,
            $ingreso->moneda ?: 'PEN',
            $ingreso->id_caja,
            $ingreso->id_cuenta_bancaria,
            self::REF_INGRESO_ECONOMICO,
            $ingreso->id_ingreso_economico,
            null,
            'Ingreso económico ' . $ingreso->numero . ': ' . $ingreso->descripcion,
            $ingreso->fecha
        );
    }

    /**
     * Realiza una transferencia de dinero entre dos destinos.
     * Genera una salida (origen) y una entrada (destino).
     */
    public function aplicarTransferenciaDinero(TransferenciaDinero $t)
    {
        $this->registrarMovimiento(
            self::TIPO_TRANSFERENCIA_SALIDA,
            (float) $t->monto,
            $t->moneda ?: 'PEN',
            $t->id_caja_origen,
            $t->id_cuenta_origen,
            self::REF_TRANSFERENCIA_DINERO,
            $t->id_transferencia_dinero,
            null,
            'Transferencia ' . $t->numero . ' (salida)',
            $t->fecha
        );

        $this->registrarMovimiento(
            self::TIPO_TRANSFERENCIA_ENTRADA,
            (float) $t->monto,
            $t->moneda ?: 'PEN',
            $t->id_caja_destino,
            $t->id_cuenta_destino,
            self::REF_TRANSFERENCIA_DINERO,
            $t->id_transferencia_dinero,
            null,
            'Transferencia ' . $t->numero . ' (entrada)',
            $t->fecha
        );
    }

    /**
     * Revierte una operación generando movimientos inversos (sin borrar el histórico).
     *
     * @param string $tipoOriginalCodigo   tipo original (ej. gasto / venta / ingreso_economico)
     * @param string $referenciaTipo       convención de referencia
     * @param int    $idReferencia
     */
    public function revertirPorReferencia($tipoOriginalCodigo, $referenciaTipo, $idReferencia, $observacion = null)
    {
        $tipoReversa = self::REVERSAS[$tipoOriginalCodigo] ?? null;
        if (!$tipoReversa) {
            throw new \Exception('No existe un tipo de reversión configurado para ' . $tipoOriginalCodigo);
        }

        $movimientos = MovimientoDinero::where('referencia_tipo', $referenciaTipo)
            ->where('id_referencia', $idReferencia)
            ->where('estado', 1)
            ->get();

        foreach ($movimientos as $m) {
            $this->registrarMovimiento(
                $tipoReversa,
                (float) $m->monto,
                $m->moneda ?: 'PEN',
                $m->id_caja,
                $m->id_cuenta_bancaria,
                $referenciaTipo,
                $idReferencia,
                $m->id_metodo_pago,
                $observacion ?: 'Reversión de ' . $referenciaTipo,
                now()
            );
        }

        return $movimientos->count();
    }

    /**
     * Efectivo esperado en una caja = apertura + entradas - salidas.
     */
    public function efectivoEsperadoCaja($idCaja)
    {
        $apertura = (float) DB::table('cajas')->where('id_caja', $idCaja)->value('monto_apertura') ?: 0;

        $entradas = DB::table('movimientos_dinero as md')
            ->join('tipos_movimiento_dinero as t', 't.id_tipo_movimiento_dinero', '=', 'md.id_tipo_movimiento_dinero')
            ->where('md.id_caja', $idCaja)
            ->where('md.estado', 1)
            ->where('t.signo', '+')
            ->sum('md.monto');

        $salidas = DB::table('movimientos_dinero as md')
            ->join('tipos_movimiento_dinero as t', 't.id_tipo_movimiento_dinero', '=', 'md.id_tipo_movimiento_dinero')
            ->where('md.id_caja', $idCaja)
            ->where('md.estado', 1)
            ->where('t.signo', '-')
            ->sum('md.monto');

        return (float) $apertura + (float) $entradas - (float) $salidas;
    }

    /**
     * Suma neta de dinero en una cuenta (saldo_actual ya actualizado por el servicio).
     */
    public function saldoCuenta($idCuenta)
    {
        return (float) DB::table('cuentas_bancarias')->where('id_cuenta_bancaria', $idCuenta)->value('saldo_actual');
    }
}
