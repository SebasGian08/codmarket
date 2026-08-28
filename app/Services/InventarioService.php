<?php

namespace App\Services;

use App\Models\Inventario;
use App\Models\Movimiento;
use App\Models\MovimientoTipo;
use App\Models\ProductoVariante;
use Illuminate\Support\Facades\DB;

class InventarioService
{
    /**
     * Aplica un movimiento de inventario sobre una variante y tienda.
     *
     * Tipos de movimiento:
     * - ingreso ............ entrada (+)
     * - venta .............. salida (-)
     * - transferencia_salida  salida (-)
     * - transferencia_entrada entrada (+)
     * - ajuste ............. se pasa cantidad con signo propio (+/-)
     *
     * Mantiene sincronizado el stock global de productos_variantes.
     */
    public function aplicar($idVariante, $idTienda, $tipo, $cantidad, $idReferencia = null, $idUsuario = null, $observacion = null)
    {
        DB::beginTransaction();

        try {
            $tipoMovimiento = MovimientoTipo::where('codigo', $tipo)->where('estado', 1)->first();

            if (!$tipoMovimiento) {
                throw new \Exception('Tipo de movimiento inválido: ' . $tipo);
            }

            $signo = $tipoMovimiento->signo === '-' ? -1 : 1;

            $delta = ($tipo === 'ajuste') ? (int) $cantidad : ($signo * abs((int) $cantidad));

            $inventario = Inventario::firstOrCreate(
                ['id_variante' => $idVariante, 'id_tienda' => $idTienda],
                ['cantidad' => 0]
            );

            $nuevaCantidad = $inventario->cantidad + $delta;

            if ($nuevaCantidad < 0) {
                throw new \Exception('Stock insuficiente en la tienda para el movimiento.');
            }

            $inventario->cantidad = $nuevaCantidad;
            $inventario->save();

            $variante = ProductoVariante::find($idVariante);

            if ($variante) {
                $variante->stock = max(0, $variante->stock + $delta);
                $variante->save();
            }

            Movimiento::create([
                'id_variante'        => $idVariante,
                'id_tienda'          => $idTienda,
                'id_tipo_movimiento' => $tipoMovimiento->id_tipo_movimiento,
                'cantidad'           => $delta,
                'id_referencia'      => $idReferencia,
                'id_usuario'         => $idUsuario,
                'fecha'              => now(),
                'observacion'        => $observacion,
            ]);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function signo($tipo)
    {
        $tipoMovimiento = MovimientoTipo::where('codigo', $tipo)->where('estado', 1)->first();

        if (!$tipoMovimiento) {
            return 0;
        }

        return $tipoMovimiento->signo === '-' ? -1 : 1;
    }
}
