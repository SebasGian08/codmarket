<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiendasTable extends Migration
{
    public function up()
    {
        Schema::create('tiendas', function (Blueprint $table) {
            $table->bigIncrements('id_tienda');

            $table->string('codigo', 10)->unique();
            $table->string('nombre', 150);
            $table->string('direccion', 255)->nullable();
            $table->string('telefono', 30)->nullable();

            $table->boolean('es_principal')->default(0);
            $table->boolean('estado')->default(1);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tiendas');
    }
}
