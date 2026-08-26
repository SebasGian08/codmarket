<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendedoresTable extends Migration
{
    public function up()
    {
        Schema::create('vendedores', function (Blueprint $table) {
            $table->bigIncrements('id_vendedor');

            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->string('nombre', 150);
            $table->string('documento', 20)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('correo', 150)->nullable();

            $table->boolean('estado')->default(1);

            $table->timestamps();

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendedores');
    }
}
