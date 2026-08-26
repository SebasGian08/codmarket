<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferenciasTable extends Migration
{
    public function up()
    {
        Schema::create('transferencias', function (Blueprint $table) {
            $table->bigIncrements('id_transferencia');

            $table->string('numero', 30)->unique();
            $table->unsignedInteger('correlativo');

            $table->unsignedBigInteger('id_tienda_origen');
            $table->unsignedBigInteger('id_tienda_destino');
            $table->unsignedBigInteger('id_usuario');

            $table->date('fecha');
            $table->text('observacion')->nullable();

            // pendiente -> en_transito -> recibida | anulada
            $table->enum('estado', ['pendiente', 'en_transito', 'recibida', 'anulada'])->default('pendiente');

            $table->timestamps();

            $table->foreign('id_tienda_origen')
                ->references('id_tienda')
                ->on('tiendas')
                ->onDelete('cascade');

            $table->foreign('id_tienda_destino')
                ->references('id_tienda')
                ->on('tiendas')
                ->onDelete('cascade');

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transferencias');
    }
}
