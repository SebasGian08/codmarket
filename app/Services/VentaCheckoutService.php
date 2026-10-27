<?php

namespace App\Services;

use App\Models\ProductoVariante;
use App\Models\MotivoDescuento;
use App\Models\TipoDescuento;
use App\Models\TipoVenta;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

/**
 * Servicio de procesamiento del cierre de venta (Checkout).
 *
 * Concentra el cálculo de descuentos por línea
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
     *         'tipo_descuento'      => ?string 'MONTO',
     *         'valor_descuento'     => ?float,
     *         'es_mayorista'        => ?bool,
     *     ],
     * }
     * @param int|null $usuarioId Id del usuario que cierra la venta.
     *
     * @return array Totales calculados: subtotal_bruto, descuento_items_total,
     *               descuento_global, total_neto.
     */
    public function procesar(Venta $venta, array $data, ?int $usuarioId = null): array
    {
        return DB::transaction(function () use ($venta, $data, $usuarioId) {
            $items = $data['items'] ?? [];

            // Obtener líneas de detalle actuales (en orden) para actualizarlas,
            // y así NO tocar el inventario (ya fue aplicado al guardar la venta).
            $detalles = $venta->detalle()->orderBy('id_venta_detalle')->get();
            $variantes = ProductoVariante::whereIn(
                'id_variante',
                $detalles->pluck('id_variante')->unique()
            )->get()->keyBy('id_variante');

            $subtotalBruto = 0.0;
            $descuentoItemsTotal = 0.0;
            $subtotalTrasItems = 0.0;

            foreach ($detalles as $i => $detalle) {
                $item = $items[$i] ?? null;
                $cantidad = (int) ($item['cantidad'] ?? $detalle->cantidad);
                $precio = (float) ($item['precio'] ?? $detalle->precio);
                $esMayorista = (bool) ($item['es_mayorista'] ?? false);

                if ($esMayorista) {
                    $variante = $variantes->get($detalle->id_variante);
                    if (!$variante || $variante->precio_mayorista === null) {
                        throw new \InvalidArgumentException('La variante seleccionada no tiene precio por mayor configurado.');
                    }
                    $precio = (float) $variante->precio_mayorista;
                }

                $brutoLinea = round($precio * $cantidad, 2);

                $tipoDesc = $item['tipo_descuento'] ?? null;
                $valorDesc = isset($item['valor_descuento']) ? (float) $item['valor_descuento'] : null;

                $descTotalLinea = $valorDesc !== null && $valorDesc > 0 && $tipoDesc === 'MONTO'
                    ? round($valorDesc * $cantidad, 2)
                    : 0.0;

                // Monto real de descuento de la línea (acotado al bruto)
                $validado = $this->validarDescuentoItem($brutoLinea, $descTotalLinea);

                $codigoTipo = $validado['descuento'] > 0 ? $tipoDesc : null;

                $datos = [
                    'id_motivo_descuento' => null,
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

            $totalNeto = round($subtotalTrasItems, 2);

            $venta->update([
                // Nuevos campos financieros
                'subtotal_bruto' => $subtotalBruto,
                'descuento_items_total' => $descuentoItemsTotal,
                'descuento_global' => 0,
                'id_motivo_descuento_global' => null,
                'total_neto' => $totalNeto,
                // Backward compat: subtotal/total usados por el resto del sistema
                'subtotal' => $subtotalBruto,
                'total' => $totalNeto,
            ]);

            return [
                'subtotal_bruto' => $subtotalBruto,
                'descuento_items_total' => $descuentoItemsTotal,
                'descuento_global' => 0,
                'total_neto' => $totalNeto,
            ];
        });
    }

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

    private function validarDescuentoItem($brutoLinea, $descuento): array
    {
        $descuento = min($descuento, $brutoLinea);
        return [
            'descuento' => round($descuento, 2),
            'subtotal_final' => round($brutoLinea - $descuento, 2),
        ];
    }

    /**
     * Resuelve el id_tipo_descuento a partir del código.
     */
    private function idTipoDescuentoPorCodigo(string $codigo): ?int
    {
        return TipoDescuento::where('codigo', $codigo)->value('id_tipo_descuento');
    }
}
