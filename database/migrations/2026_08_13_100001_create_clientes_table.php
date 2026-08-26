<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id_cliente');

            $table->string('nombre', 150);
            $table->unsignedBigInteger('id_tipo_documento')->nullable();
            $table->string('numero_documento', 20)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('correo', 150)->nullable();
            $table->string('direccion', 255)->nullable();

            $table->boolean('estado')->default(1);

            $table->timestamps();

            $table->foreign('id_tipo_documento')
                ->references('id_tipo_documento')
                ->on('tipo_documento')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
