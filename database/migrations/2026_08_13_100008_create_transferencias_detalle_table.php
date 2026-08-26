<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferenciasDetalleTable extends Migration
{
    public function up()
    {
        Schema::create('transferencias_detalle', function (Blueprint $table) {
            $table->bigIncrements('id_transferencia_detalle');

            $table->unsignedBigInteger('id_transferencia');
            $table->unsignedBigInteger('id_variante');

            $table->integer('cantidad');

            $table->timestamps();

            $table->foreign('id_transferencia')
                ->references('id_transferencia')
                ->on('transferencias')
                ->onDelete('cascade');

            $table->foreign('id_variante')
                ->references('id_variante')
                ->on('productos_variantes')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transferencias_detalle');
    }
}
