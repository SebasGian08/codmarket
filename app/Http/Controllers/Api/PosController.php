<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VentaCheckoutService;

class PosController extends Controller
{
    protected $checkout;

    public function __construct(VentaCheckoutService $checkout)
    {
        $this->checkout = $checkout;
    }

    /**
     * Configuración de la ventana modal de cobro (Checkout).
     * Retorna tipos de venta activos y motivos de descuento (ITEM/CABECERA).
     */
    public function configuracionCierre()
    {
        return response()->json($this->checkout->getCheckoutConfig());
    }
}
