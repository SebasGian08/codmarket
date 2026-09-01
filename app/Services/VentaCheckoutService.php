<?php

namespace App\Services;

use App\Models\MotivoDescuento;
use App\Models\ReglaDescuento;
use App\Models\TipoDescuento;
use App\Models\TipoVenta;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

/**
 * Servicio de procesamiento del cierre de venta (Checkout).
 *
 * Concentra el cálculo de descuentos (por ítem, por reglas de volumen y global)
 * y la persistencia de la cabecera `ventas` y las líneas `ventas_detalle`
 * dentro de una transacción de base de datos.
 */
class VentaCheckoutService
{
    /**
     * Aplica descuentos a una venta pendiente y recalcula sus totales.
     *
     * @param Venta   $venta     Venta ya existente con estado_cobro = 'pendiente'
     * @param array   $data      {
     *     'items' => [
     *         'id_variante',
     *         'cantidad',
     *         'precio',
     *         'id_motivo_descuento' => ?int,
     *         'tipo_descuento'      => ?string 'PORCENTAJE'|'MONTO',
     *         'valor_descuento'     => ?float,
     *     ],
     *     'id_tipo_venta'           => ?int,
     *     'id_motivo_descuento_global' => ?int,
     *     'descuento_global'        => ?float,
     * }
     * @param int|null $usuarioId Id del usuario que cierra la venta.
     *
     * @return array Totales calculados: subtotal_bruto, descuento_items_total,
     *               descuento_global, total_neto.
     */
    public function procesar(Venta $venta, array $data, ?int $usuarioId = null): array
    {
        return DB::transaction(function () use ($venta, $data, $usuarioId) {
            $tiposVentaId = $data['id_tipo_venta'] ?? $venta->id_tipo_venta;
            $items = $data['items'] ?? [];

            // Descuento global (monto fijo) enviado desde el checkout
            $descuentoGlobal = isset($data['descuento_global'])
                ? (float) max(0, $data['descuento_global'])
                : 0.0;

            // Obtener líneas de detalle actuales (en orden) para actualizarlas,
            // y así NO tocar el inventario (ya fue aplicado al guardar la venta).
            $detalles = $venta->detalle()->orderBy('id_venta_detalle')->get();

            $subtotalBruto = 0.0;
            $descuentoItemsTotal = 0.0;
            $subtotalTrasItems = 0.0;

            foreach ($detalles as $i => $detalle) {
                $item = $items[$i] ?? null;
                $cantidad = (int) ($item['cantidad'] ?? $detalle->cantidad);
                $precio = (float) ($item['precio'] ?? $detalle->precio);

                $brutoLinea = round($precio * $cantidad, 2);

                // Descuento por línea: manual (del checkout) o por regla de volumen
                $idMotivo = isset($item['id_motivo_descuento']) && $item['id_motivo_descuento']
                    ? (int) $item['id_motivo_descuento'] : null;
                $tipoDesc = $item['tipo_descuento'] ?? null;
                $valorDesc = isset($item['valor_descuento']) ? (float) $item['valor_descuento'] : null;
                $tipoRegla = null;
                $valorRegla = null;

                if ($idMotivo && $valorDesc !== null && $valorDesc > 0 && in_array($tipoDesc, ['PORCENTAJE', 'MONTO'])) {
                    $descTotalLinea = $this->aplicarDescuentoLinea($brutoLinea, $cantidad, $precio, $tipoDesc, $valorDesc);
                } else {
                    // Evaluar regla de volumen automática por si aplica (solo descuento, no cambia el motivo)
                    list($tipoRegla, $valorRegla) = $this->reglaVolumenAplicable(
                        count($detalles),
                        $tiposVentaId ?: $venta->id_tipo_venta,
                        $cantidad
                    );
                    $descTotalLinea = 0.0;
                    if ($valorRegla > 0) {
                        $descTotalLinea = $this->aplicarDescuentoLinea($brutoLinea, $cantidad, $precio, $tipoRegla, $valorRegla);
                    }
                }

                // Validar el motivo: solo los de tipo ITEM son válidos a nivel de línea
                if ($idMotivo) {
                    $motivo = MotivoDescuento::find($idMotivo);
                    if (!$motivo || $motivo->aplica_a !== 'ITEM') {
                        $idMotivo = null;
                    }
                }

                // Monto real de descuento de la línea (acotado al bruto)
                $validado = $this->validarDescuentoItem($brutoLinea, $descTotalLinea);

                $codigoTipo = $validado['descuento'] > 0 ? ($tipoDesc ?: $tipoRegla) : null;

                $datos = [
                    'id_motivo_descuento' => $idMotivo && $validado['descuento'] > 0 ? $idMotivo : null,
                    'id_tipo_descuento' => $codigoTipo ? $this->idTipoDescuentoPorCodigo($codigoTipo) : null,
                    'valor_descuento_unitario' => $validado['descuento'] > 0
                        ? round($validado['descuento'] / max(1, $cantidad), 2)
                        : 0,
                    'descuento_total_item' => $validado['descuento'],
                    'subtotal_final' => $validado['subtotal_final'],
                    // Backward compat: subtotal = bruto de la línea
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                    'subtotal' => $brutoLinea,
                ];

                $detalle->update($datos);

                $subtotalBruto += $brutoLinea;
                $descuentoItemsTotal += $datos['descuento_total_item'];
                $subtotalTrasItems += $datos['subtotal_final'];
            }

            $subtotalBruto = round($subtotalBruto, 2);
            $descuentoItemsTotal = round($descuentoItemsTotal, 2);

            // Descuento global validado
            $descuentoGlobal = round(min($descuentoGlobal, $subtotalTrasItems), 2);
            $totalNeto = round(max(0, $subtotalTrasItems - $descuentoGlobal), 2);

            // Id motivo global (solo si es CABECERA) y se aplica descuento
            $idMotivoGlobal = $data['id_motivo_descuento_global'] ?? null;
            if ($idMotivoGlobal) {
                $motivoGlobal = MotivoDescuento::find($idMotivoGlobal);
                if (!$motivoGlobal || $motivoGlobal->aplica_a !== 'CABECERA' || $descuentoGlobal <= 0) {
                    $idMotivoGlobal = null;
                }
            }

            $venta->update([
                // Nuevos campos financieros
                'id_tipo_venta' => $tiposVentaId ?: null,
                'subtotal_bruto' => $subtotalBruto,
                'descuento_items_total' => $descuentoItemsTotal,
                'descuento_global' => $descuentoGlobal,
                'id_motivo_descuento_global' => $idMotivoGlobal,
                'total_neto' => $totalNeto,
                // Backward compat: subtotal/total usados por el resto del sistema
                'subtotal' => $subtotalBruto,
                'total' => $totalNeto,
            ]);

            return [
                'subtotal_bruto' => $subtotalBruto,
                'descuento_items_total' => $descuentoItemsTotal,
                'descuento_global' => $descuentoGlobal,
                'total_neto' => $totalNeto,
            ];
        });
    }

    /**
     * Datos de configuración para armar los selectores del modal de cobro (POS).
     */
    public function getCheckoutConfig(): array
    {
        $tiposVenta = TipoVenta::where('estado', 1)
            ->orderBy('nombre', 'asc')
            ->get(['id_tipo_venta', 'nombre']);

        $motivos = MotivoDescuento::where('estado', 1)
            ->orderBy('aplica_a', 'asc')
            ->orderBy('nombre', 'asc')
            ->get(['id_motivo_descuento', 'nombre', 'aplica_a']);

        return [
            'tipos_venta' => $tiposVenta,
            'motivos_descuento' => $motivos,
        ];
    }

    /* ================================================================
       HELPERS PRIVADOS
       ================================================================ */

    private function aplicarDescuentoLinea($brutoLinea, $cantidad, $precio, $tipoDesc, $valorDesc): float
    {
        if ($tipoDesc === 'PORCENTAJE') {
            $pct = min(100, (float) $valorDesc);
            return round($brutoLinea * ($pct / 100), 2);
        }

        // MONTO: por unidad
        return round((float) $valorDesc * $cantidad, 2);
    }

    private function validarDescuentoItem($brutoLinea, $descuento): array
    {
        $descuento = min($descuento, $brutoLinea);
        return [
            'descuento' => round($descuento, 2),
            'subtotal_final' => round($brutoLinea - $descuento, 2),
        ];
    }

    /**
     * Busca una regla de volumen aplicable según la cantidad de ítems o de un ítem.
     * Devuelve [tipo_descuento?, valor?].
     */
    private function reglaVolumenAplicable(int $totalItems, $idTipoVenta, int $cantidad): array
    {
        $total = max($totalItems, $cantidad);

        $regla = ReglaDescuento::with('tipoDescuento')
            ->where('estado', 1)
            ->where(function ($q) use ($total) {
                $q->whereNull('cantidad_min')->orWhere('cantidad_min', '<=', $total);
            })
            ->where(function ($q) use ($total) {
                $q->whereNull('cantidad_max')->orWhere('cantidad_max', '>=', $total);
            })
            ->where(function ($q) use ($idTipoVenta) {
                if ($idTipoVenta) {
                    $q->where('id_tipo_venta', $idTipoVenta);
                } else {
                    $q->whereNull('id_tipo_venta');
                }
            })
            ->orderBy('id_regla_descuento', 'asc')
            ->first();

        if (!$regla || !$regla->tipoDescuento) {
            return [null, null];
        }

        return [$regla->tipoDescuento->codigo, (float) $regla->valor];
    }

    /**
     * Resuelve el id_tipo_descuento a partir del código (PORCENTAJE|MONTO).
     */
    private function idTipoDescuentoPorCodigo(string $codigo): ?int
    {
        return TipoDescuento::where('codigo', $codigo)->value('id_tipo_descuento');
    }
}
