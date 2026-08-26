<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasTable extends Migration
{
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->bigIncrements('id_venta');

            $table->string('numero', 30)->unique();
            $table->unsignedInteger('correlativo');

            $table->unsignedBigInteger('id_caja');
            $table->unsignedBigInteger('id_tienda');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->string('nombre_cliente', 150)->nullable();

            $table->enum('tipo_pago', ['efectivo', 'tarjeta', 'transferencia', 'otro'])->default('efectivo');

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            // 1 = registrada, 0 = anulada
            $table->boolean('estado')->default(1);

            $table->timestamps();

            $table->foreign('id_caja')
                ->references('id_caja')
                ->on('cajas')
                ->onDelete('cascade');

            $table->foreign('id_tienda')
                ->references('id_tienda')
                ->on('tiendas')
                ->onDelete('cascade');

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->onDelete('cascade');

            $table->foreign('id_cliente')
                ->references('id_cliente')
                ->on('clientes')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventas');
    }
}
