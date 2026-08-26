<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientosTable extends Migration
{
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->bigIncrements('id_movimiento');

            $table->unsignedBigInteger('id_variante');
            $table->unsignedBigInteger('id_tienda');

            // ingreso | venta | transferencia_salida | transferencia_entrada | ajuste
            $table->enum('tipo', ['ingreso', 'venta', 'transferencia_salida', 'transferencia_entrada', 'ajuste']);

            // con signo: + entrada, - salida
            $table->integer('cantidad');

            // id del documento origen (venta, ingreso o transferencia)
            $table->unsignedBigInteger('id_referencia')->nullable();
            $table->unsignedBigInteger('id_usuario')->nullable();

            $table->dateTime('fecha');
            $table->text('observacion')->nullable();

            $table->timestamps();

            $table->foreign('id_variante')
                ->references('id_variante')
                ->on('productos_variantes')
                ->onDelete('cascade');

            $table->foreign('id_tienda')
                ->references('id_tienda')
                ->on('tiendas')
                ->onDelete('cascade');

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('movimientos');
    }
}
