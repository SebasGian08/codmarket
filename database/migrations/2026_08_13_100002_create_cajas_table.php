<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajasTable extends Migration
{
    public function up()
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->bigIncrements('id_caja');

            $table->unsignedBigInteger('id_tienda');
            $table->unsignedBigInteger('id_usuario');

            $table->string('nombre', 100)->default('Caja Principal');
            $table->decimal('monto_apertura', 10, 2)->default(0);
            $table->decimal('monto_cierre', 10, 2)->nullable();

            $table->timestamp('fecha_apertura')->nullable();
            $table->timestamp('fecha_cierre')->nullable();

            // 1 = abierta, 0 = cerrada
            $table->boolean('estado')->default(1);

            $table->timestamps();

            $table->foreign('id_tienda')
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
        Schema::dropIfExists('cajas');
    }
}
