<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventariosTable extends Migration
{
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->bigIncrements('id_inventario');

            $table->unsignedBigInteger('id_variante');
            $table->unsignedBigInteger('id_tienda');

            $table->integer('cantidad')->default(0);

            $table->timestamps();

            $table->unique(['id_variante', 'id_tienda']);

            $table->foreign('id_variante')
                ->references('id_variante')
                ->on('productos_variantes')
                ->onDelete('cascade');

            $table->foreign('id_tienda')
                ->references('id_tienda')
                ->on('tiendas')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventarios');
    }
}
