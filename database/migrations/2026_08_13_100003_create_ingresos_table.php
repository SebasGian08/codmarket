<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngresosTable extends Migration
{
    public function up()
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->bigIncrements('id_ingreso');

            $table->string('numero', 30)->unique();
            $table->unsignedInteger('correlativo');

            // compra = compra a proveedor | ajuste = ajuste/stock inicial/corrección
            $table->enum('tipo', ['compra', 'ajuste'])->default('compra');

            $table->unsignedBigInteger('id_proveedor')->nullable();
            $table->unsignedBigInteger('id_tienda');
            $table->unsignedBigInteger('id_usuario');

            $table->date('fecha');
            $table->text('observacion')->nullable();

            // 1 = registrado, 0 = anulado
            $table->boolean('estado')->default(1);

            $table->timestamps();

            $table->foreign('id_proveedor')
                ->references('id_proveedor')
                ->on('proveedores')
                ->onDelete('set null');

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
        Schema::dropIfExists('ingresos');
    }
}
