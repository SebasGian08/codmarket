<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id_usuario');

            $table->unsignedBigInteger('id_rol');

            $table->string('nombres', 100);
            $table->string('apellidos', 100);

            $table->string('email', 150)->unique();
            $table->string('password');

            $table->string('telefono', 20)->nullable();

            $table->boolean('estado')->default(1);

            $table->timestamps();

            $table->foreign('id_rol')
                ->references('id_rol')
                ->on('roles')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }

    
}