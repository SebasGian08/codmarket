<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateVentasMetodoPagoTable extends Migration
{
    public function up()
    {
        // Métodos de pago por defecto (idempotente)
        $metodos = [
            ['nombre' => 'Efectivo', 'codigo' => 'efectivo'],
            ['nombre' => 'Tarjeta', 'codigo' => 'tarjeta'],
            ['nombre' => 'Transferencia', 'codigo' => 'transferencia'],
            ['nombre' => 'Otro', 'codigo' => 'otro'],
        ];

        foreach ($metodos as $metodo) {
            if (!DB::table('metodos_pagos')->where('codigo', $metodo['codigo'])->exists()) {
                DB::table('metodos_pagos')->insert($metodo + [
                    'estado' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('ventas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_metodo_pago')->nullable()->after('nombre_cliente');
        });

        // Mapear los valores antiguos del enum al nuevo maestro
        $map = DB::table('metodos_pagos')->pluck('id_metodo_pago', 'codigo');

        foreach (DB::table('ventas')->get() as $venta) {
            DB::table('ventas')
                ->where('id_venta', $venta->id_venta)
                ->update(['id_metodo_pago' => $map->get($venta->tipo_pago) ?: null]);
        }

        DB::statement('ALTER TABLE `ventas` DROP COLUMN `tipo_pago`');

        Schema::table('ventas', function (Blueprint $table) {
            $table->foreign('id_metodo_pago')
                ->references('id_metodo_pago')
                ->on('metodos_pagos')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['id_metodo_pago']);
            $table->dropColumn('id_metodo_pago');
        });

        Schema::table('ventas', function (Blueprint $table) {
            $table->enum('tipo_pago', ['efectivo', 'tarjeta', 'transferencia', 'otro'])->default('efectivo');
        });
    }
}
