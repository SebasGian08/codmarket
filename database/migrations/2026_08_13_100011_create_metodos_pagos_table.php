<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetodosPagosTable extends Migration
{
    public function up()
    {
        Schema::create('metodos_pagos', function (Blueprint $table) {
            $table->bigIncrements('id_metodo_pago');

            $table->string('nombre', 100);
            $table->string('codigo', 30)->nullable();

            $table->boolean('estado')->default(1);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('metodos_pagos');
    }
}
