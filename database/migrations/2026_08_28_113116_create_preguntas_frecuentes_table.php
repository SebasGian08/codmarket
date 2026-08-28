<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreguntasFrecuentesTable extends Migration
{
    public function up()
    {
        Schema::create('preguntas_frecuentes', function (Blueprint $table) {
            $table->bigIncrements('id_pregunta_frecuente');
            $table->string('pregunta', 255);
            $table->text('respuesta');
            $table->integer('orden')->default(0);
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('preguntas_frecuentes');
    }
}