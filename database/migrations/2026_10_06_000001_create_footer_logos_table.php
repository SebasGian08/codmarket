<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFooterLogosTable extends Migration
{
    public function up()
    {
        Schema::create('footer_logos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titulo', 150)->nullable();
            $table->string('imagen', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('footer_logos');
    }
}
