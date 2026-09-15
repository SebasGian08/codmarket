<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('empresa', 'empresa_ventajas')) {
            Schema::table('empresa', function (Blueprint $table) {
                $table->longText('empresa_ventajas')->nullable();
            });
        }

        $empresa = DB::table('empresa')->first();
        if ($empresa && empty($empresa->empresa_ventajas)) {
            DB::table('empresa')->where('id_empresa', $empresa->id_empresa)->update([
                'empresa_ventajas' => json_encode([
                    ['icono' => 'fas fa-shipping-fast', 'titulo' => 'Envíos Rápidos', 'descripcion' => 'Realizamos entregas ágiles y seguras para que recibas tus productos en el menor tiempo posible.'],
                    ['icono' => 'fas fa-shield-alt', 'titulo' => 'Compra 100% Segura', 'descripcion' => 'Protegemos cada transacción con métodos de pago confiables y seguridad avanzada.'],
                    ['icono' => 'fab fa-whatsapp', 'titulo' => 'Atención Personalizada', 'descripcion' => 'Nuestro equipo está listo para ayudarte antes, durante y después de tu compra.'],
                ], JSON_UNESCAPED_UNICODE),
            ]);
        }
    }

    public function down()
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->dropColumn('empresa_ventajas');
        });
    }
};
