<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasDetalleTable extends Migration
{
    public function up()
    {
        Schema::create('ventas_detalle', function (Blueprint $table) {
            $table->bigIncrements('id_venta_detalle');

            $table->unsignedBigInteger('id_venta');
            $table->unsignedBigInteger('id_variante');

            $table->integer('cantidad');
            $table->decimal('precio', 10, 2);
            $table->decimal('subtotal', 10, 2);

            $table->timestamps();

            $table->foreign('id_venta')
                ->references('id_venta')
                ->on('ventas')
                ->onDelete('cascade');

            $table->foreign('id_variante')
                ->references('id_variante')
                ->on('productos_variantes')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventas_detalle');
    }
}
