<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExtendIngresosForAdjustmentsAndReturns extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE ingresos MODIFY tipo ENUM('compra', 'ajuste', 'ajuste_suma', 'ajuste_resta', 'devolucion') NOT NULL DEFAULT 'compra'");

        Schema::table('ingresos', function ($table) {
            $table->unsignedBigInteger('id_venta')->nullable()->after('id_proveedor');
            $table->foreign('id_venta')
                ->references('id_venta')
                ->on('ventas')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        if (DB::table('ingresos')->whereIn('tipo', ['ajuste_suma', 'ajuste_resta', 'devolucion'])->exists()) {
            throw new RuntimeException('No se puede revertir: hay ingresos con los nuevos tipos registrados.');
        }

        Schema::table('ingresos', function ($table) {
            $table->dropForeign(['id_venta']);
            $table->dropColumn('id_venta');
        });

        DB::statement("ALTER TABLE ingresos MODIFY tipo ENUM('compra', 'ajuste') NOT NULL DEFAULT 'compra'");
    }
}
