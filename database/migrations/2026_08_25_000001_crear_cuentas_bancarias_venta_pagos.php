<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CrearCuentasBancariasVentaPagos extends Migration
{
    public function up()
    {
        // 1. Tabla cuentas_bancarias (solo si no existe)
        if (!Schema::hasTable('cuentas_bancarias')) {
            Schema::create('cuentas_bancarias', function (Blueprint $table) {
                $table->increments('id_cuenta_bancaria');
                $table->string('nombre', 100);
                $table->string('tipo', 50)->default('caja');
                $table->string('moneda', 10)->default('PEN');
                $table->decimal('saldo', 12, 2)->default(0);
                $table->tinyInteger('estado')->default(1);
                $table->timestamps();
            });
        }

        // 2. Agregar estado_cobro si no existe
        $hasEstadoCobro = DB::select("SHOW COLUMNS FROM ventas LIKE 'estado_cobro'");
        if (empty($hasEstadoCobro)) {
            DB::statement("ALTER TABLE ventas
                ADD COLUMN estado_cobro ENUM('pendiente','cerrado') NOT NULL DEFAULT 'pendiente' AFTER estado,
                ADD COLUMN fecha_cierre TIMESTAMP NULL AFTER estado_cobro,
                ADD COLUMN usuario_cierre BIGINT UNSIGNED NULL AFTER fecha_cierre
            ");

            Schema::table('ventas', function (Blueprint $table) {
                $table->foreign('usuario_cierre')
                    ->references('id_usuario')->on('usuarios')
                    ->nullOnDelete();
                $table->index('estado_cobro');
            });
        }

        // 3. Hacer id_metodo_pago nullable si no lo es
        $columnInfo = DB::select("SHOW COLUMNS FROM ventas WHERE Field = 'id_metodo_pago'");
        if (!empty($columnInfo) && $columnInfo[0]->Null === 'NO') {
            DB::statement("ALTER TABLE ventas MODIFY COLUMN id_metodo_pago INT UNSIGNED NULL");
        }

        // 4. Tabla venta_pagos (solo si no existe)
        if (!Schema::hasTable('venta_pagos')) {
            Schema::create('venta_pagos', function (Blueprint $table) {
                $table->increments('id_venta_pago');
                $table->unsignedBigInteger('id_venta');
                $table->unsignedInteger('id_metodo_pago');
                $table->unsignedBigInteger('id_cuenta_bancaria');
                $table->decimal('monto', 12, 2);
                $table->string('moneda', 10)->default('PEN');
                $table->unsignedBigInteger('id_usuario_registro')->nullable();
                $table->timestamps();

                $table->foreign('id_venta')
                    ->references('id_venta')->on('ventas')
                    ->cascadeOnDelete();
                $table->foreign('id_metodo_pago')
                    ->references('id_metodo_pago')->on('metodos_pagos')
                    ->restrictOnDelete();
                $table->foreign('id_cuenta_bancaria')
                    ->references('id_cuenta_bancaria')->on('cuentas_bancarias')
                    ->restrictOnDelete();
                $table->foreign('id_usuario_registro')
                    ->references('id_usuario')->on('usuarios')
                    ->nullOnDelete();

                $table->index('id_venta');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('venta_pagos');

        if (Schema::hasTable('ventas')) {
            $hasCol = DB::select("SHOW COLUMNS FROM ventas LIKE 'estado_cobro'");
            if (!empty($hasCol)) {
                Schema::table('ventas', function (Blueprint $table) {
                    $table->dropForeign(['usuario_cierre']);
                    $table->dropIndex(['estado_cobro']);
                });

                DB::statement("ALTER TABLE ventas
                    DROP COLUMN estado_cobro,
                    DROP COLUMN fecha_cierre,
                    DROP COLUMN usuario_cierre
                ");
            }
        }

        Schema::dropIfExists('cuentas_bancarias');
    }
}
