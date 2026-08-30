<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CrearMovimientosDinero extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('movimientos_dinero')) {
            Schema::create('movimientos_dinero', function (Blueprint $table) {
                $table->bigIncrements('id_movimiento_dinero');

                $table->unsignedBigInteger('id_tipo_movimiento_dinero');

                // Destino financiero: exactamente uno de los dos (caja XOR cuenta)
                $table->unsignedBigInteger('id_caja')->nullable();
                $table->unsignedBigInteger('id_cuenta_bancaria')->nullable();

                // Origen/referencia con convención centralizada (multimorph explícito)
                $table->string('referencia_tipo', 40)->nullable();
                $table->unsignedBigInteger('id_referencia')->nullable();

                $table->unsignedBigInteger('id_metodo_pago')->nullable();

                $table->decimal('monto', 12, 2);
                $table->string('moneda', 10)->default('PEN');
                $table->dateTime('fecha');
                $table->text('observacion')->nullable();

                $table->unsignedBigInteger('id_usuario_registro')->nullable();
                $table->boolean('estado')->default(1);
                $table->timestamps();
            });

            Schema::table('movimientos_dinero', function (Blueprint $table) {
                $table->foreign('id_tipo_movimiento_dinero')
                    ->references('id_tipo_movimiento_dinero')->on('tipos_movimiento_dinero');
                $table->foreign('id_caja')
                    ->references('id_caja')->on('cajas')->nullOnDelete();
                $table->foreign('id_cuenta_bancaria')
                    ->references('id_cuenta_bancaria')->on('cuentas_bancarias')->nullOnDelete();
                $table->foreign('id_metodo_pago')
                    ->references('id_metodo_pago')->on('metodos_pagos');
                $table->foreign('id_usuario_registro')
                    ->references('id_usuario')->on('usuarios');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('movimientos_dinero')) {
            $foreigns = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'movimientos_dinero'
                AND REFERENCED_TABLE_NAME IS NOT NULL");
            foreach ($foreigns as $fk) {
                DB::statement('ALTER TABLE movimientos_dinero DROP FOREIGN KEY `' . $fk->CONSTRAINT_NAME . '`');
            }
            Schema::dropIfExists('movimientos_dinero');
        }
    }
}
