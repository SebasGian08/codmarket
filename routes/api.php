<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\PosController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
| Configuración del cierre de venta (Checkout POS)
| Retorna tipos de venta y motivos de descuento para armar los combos.
*/
Route::get('/pos/cierre-config', [PosController::class, 'configuracionCierre']);

