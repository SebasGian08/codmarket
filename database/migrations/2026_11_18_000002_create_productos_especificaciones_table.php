<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos_especificaciones', function (Blueprint $table) {
            $table->bigIncrements('id_especificacion');
            $table->bigInteger('id_producto');
            $table->string('nombre', 150);
            $table->text('valor');
            $table->unsignedInteger('orden')->default(0);

            $table->foreign('id_producto', 'productos_especificaciones_producto_fk')
                ->references('id_producto')
                ->on('productos')
                ->onDelete('cascade');
            $table->index(
                ['id_producto', 'orden'],
                'productos_especificaciones_producto_orden_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos_especificaciones');
    }
};
