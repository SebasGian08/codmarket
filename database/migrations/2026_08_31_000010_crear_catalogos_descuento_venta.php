<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CrearCatalogosDescuentoVenta extends Migration
{
    public function up()
    {
        /* ================= TIPOS DE VENTA ================= */
        Schema::create('tipos_venta', function (Blueprint $table) {
            $table->bigIncrements('id_tipo_venta');
            $table->string('nombre', 100);
            $table->string('descripcion', 255)->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });

        /* ================= MOTIVOS DE DESCUENTO ================= */
        Schema::create('motivos_descuento', function (Blueprint $table) {
            $table->bigIncrements('id_motivo_descuento');
            $table->string('nombre', 100);
            $table->string('descripcion', 255)->nullable();
            $table->enum('aplica_a', ['ITEM', 'CABECERA'])->default('ITEM');
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });

        /* ================= TIPOS DE DESCUENTO ================= */
        Schema::create('tipos_descuento', function (Blueprint $table) {
            $table->bigIncrements('id_tipo_descuento');
            $table->string('codigo', 30)->unique();
            $table->string('nombre', 100);
            $table->string('descripcion', 255)->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });

        /* ================= REGLAS DE DESCUENTO ================= */
        Schema::create('reglas_descuento', function (Blueprint $table) {
            $table->bigIncrements('id_regla_descuento');
            $table->string('nombre', 100);
            $table->string('descripcion', 255)->nullable();
            $table->unsignedBigInteger('id_tipo_descuento')->default(1);
            $table->decimal('valor', 10, 2)->default(0);
            // Rango de cantidad de ítems del carrito donde aplica
            $table->unsignedInteger('cantidad_min')->nullable();
            $table->unsignedInteger('cantidad_max')->nullable();
            // Tipo de venta al que aplica (opcional, null = todos)
            $table->unsignedBigInteger('id_tipo_venta')->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();

            $table->foreign('id_tipo_descuento')
                ->references('id_tipo_descuento')
                ->on('tipos_descuento')
                ->onDelete('restrict');

            $table->foreign('id_tipo_venta')
                ->references('id_tipo_venta')
                ->on('tipos_venta')
                ->onDelete('set null');
        });

        /* ================= DATOS SEMILLA ================= */
        DB::table('tipos_venta')->insert([
            [
                'nombre' => 'Venta Normal',
                'descripcion' => 'Venta de productos a precio regular',
                'estado' => 1,
            ],
            [
                'nombre' => 'Venta de Vestidos Fallados',
                'descripcion' => 'Venta de prendas con fallas con descuento aplicado en el cierre',
                'estado' => 1,
            ],
        ]);

        DB::table('motivos_descuento')->insert([
            [
                'nombre' => 'Prenda fallada',
                'descripcion' => 'Prenda con defecto o falla que justifica descuento por ítem',
                'aplica_a' => 'ITEM',
                'estado' => 1,
            ],
            [
                'nombre' => 'Vestido fallado (lote)',
                'descripcion' => 'Lote de vestidos fallados con descuento global en la venta',
                'aplica_a' => 'CABECERA',
                'estado' => 1,
            ],
            [
                'nombre' => 'Descuento especial',
                'descripcion' => 'Descuento especial aprobado sobre toda la venta',
                'aplica_a' => 'CABECERA',
                'estado' => 1,
            ],
        ]);

        /* ================= TIPOS DE DESCUENTO (catálogo) ================= */
        DB::table('tipos_descuento')->insert([
            [
                'codigo' => 'PORCENTAJE',
                'nombre' => 'Porcentaje',
                'descripcion' => 'Descuento expresado como porcentaje (%)',
                'estado' => 1,
            ],
            [
                'codigo' => 'MONTO',
                'nombre' => 'Monto',
                'descripcion' => 'Descuento expresado como monto fijo (S/)',
                'estado' => 1,
            ],
        ]);

        DB::table('reglas_descuento')->insert([
            [
                'nombre' => 'Vestido fallado lote (5% por prenda)',
                'descripcion' => '5% de descuento por cada prenda cuando se vende un lote de vestidos fallados',
                'id_tipo_descuento' => 1, // PORCENTAJE
                'valor' => 5.00,
                'cantidad_min' => 2,
                'cantidad_max' => null,
                'id_tipo_venta' => 2, // Venta de Vestidos Fallados
                'estado' => 1,
            ],
            [
                'nombre' => 'Vestido fallado (S/10 por prenda)',
                'descripcion' => 'S/ 10 de descuento por prenda en ventas de vestidos fallados',
                'id_tipo_descuento' => 2, // MONTO
                'valor' => 10.00,
                'cantidad_min' => 1,
                'cantidad_max' => null,
                'id_tipo_venta' => 2, // Venta de Vestidos Fallados
                'estado' => 1,
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('reglas_descuento');
        Schema::dropIfExists('tipos_descuento');
        Schema::dropIfExists('motivos_descuento');
        Schema::dropIfExists('tipos_venta');
    }
}
