<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CrearGastos extends Migration
{
    public function up()
    {
        // 1. Tabla tipos_gastos
        if (!Schema::hasTable('tipos_gastos')) {
            Schema::create('tipos_gastos', function (Blueprint $table) {
                $table->increments('id_tipo_gasto');
                $table->string('nombre', 100);
                $table->tinyInteger('estado')->default(1);
                $table->timestamps();
            });
        }

        // 2. Tabla gastos
        if (!Schema::hasTable('gastos')) {
            Schema::create('gastos', function (Blueprint $table) {
                $table->bigIncrements('id_gasto');
                $table->string('numero', 30)->unique();
                $table->unsignedInteger('correlativo');
                $table->unsignedInteger('id_tipo_gasto');
                $table->unsignedBigInteger('id_tienda');
                $table->unsignedBigInteger('id_caja')->nullable();
                $table->unsignedBigInteger('id_cuenta_bancaria')->nullable();
                $table->unsignedBigInteger('id_usuario');
                $table->date('fecha');
                $table->string('descripcion', 500);
                $table->decimal('monto', 12, 2);
                $table->string('moneda', 10)->default('PEN');
                $table->text('observacion')->nullable();
                $table->boolean('estado')->default(1);
                $table->timestamps();

                $table->foreign('id_tipo_gasto')
                    ->references('id_tipo_gasto')->on('tipos_gastos')
                    ->restrictOnDelete();
                $table->foreign('id_tienda')
                    ->references('id_tienda')->on('tiendas')
                    ->restrictOnDelete();
                $table->foreign('id_caja')
                    ->references('id_caja')->on('cajas')
                    ->nullOnDelete();
                $table->foreign('id_cuenta_bancaria')
                    ->references('id_cuenta_bancaria')->on('cuentas_bancarias')
                    ->nullOnDelete();
                $table->foreign('id_usuario')
                    ->references('id_usuario')->on('usuarios')
                    ->restrictOnDelete();

                $table->index('fecha');
                $table->index('estado');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('gastos');
        Schema::dropIfExists('tipos_gastos');
    }
}
