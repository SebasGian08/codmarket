<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngresosDetalleTable extends Migration
{
    public function up()
    {
        Schema::create('ingresos_detalle', function (Blueprint $table) {
            $table->bigIncrements('id_ingreso_detalle');

            $table->unsignedBigInteger('id_ingreso');
            $table->unsignedBigInteger('id_variante');

            $table->integer('cantidad');
            $table->decimal('costo', 10, 2)->default(0);

            $table->timestamps();

            $table->foreign('id_ingreso')
                ->references('id_ingreso')
                ->on('ingresos')
                ->onDelete('cascade');

            $table->foreign('id_variante')
                ->references('id_variante')
                ->on('productos_variantes')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ingresos_detalle');
    }
}
