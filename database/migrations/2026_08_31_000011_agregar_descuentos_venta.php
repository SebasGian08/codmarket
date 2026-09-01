<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarDescuentosVenta extends Migration
{
    public function up()
    {
        // ============ VENTAS (cabecera) ============
        Schema::table('ventas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tipo_venta')->nullable()->after('id_vendedor');
            $table->decimal('subtotal_bruto', 10, 2)->default(0)->after('total');
            $table->decimal('descuento_items_total', 10, 2)->default(0)->after('subtotal_bruto');
            $table->decimal('descuento_global', 10, 2)->default(0)->after('descuento_items_total');
            $table->unsignedBigInteger('id_motivo_descuento_global')->nullable()->after('descuento_global');
            $table->decimal('total_neto', 10, 2)->default(0)->after('id_motivo_descuento_global');

            $table->foreign('id_tipo_venta')
                ->references('id_tipo_venta')
                ->on('tipos_venta')
                ->onDelete('set null');

            $table->foreign('id_motivo_descuento_global')
                ->references('id_motivo_descuento')
                ->on('motivos_descuento')
                ->onDelete('set null');
        });

        // ============ VENTAS_DETALLE (línea) ============
        Schema::table('ventas_detalle', function (Blueprint $table) {
            $table->unsignedBigInteger('id_motivo_descuento')->nullable()->after('subtotal');
            $table->unsignedBigInteger('id_tipo_descuento')->nullable()->after('id_motivo_descuento');
            $table->decimal('valor_descuento_unitario', 10, 2)->default(0)->after('id_tipo_descuento');
            $table->decimal('descuento_total_item', 10, 2)->default(0)->after('valor_descuento_unitario');
            $table->decimal('subtotal_final', 10, 2)->default(0)->after('descuento_total_item');

            $table->foreign('id_motivo_descuento')
                ->references('id_motivo_descuento')
                ->on('motivos_descuento')
                ->onDelete('set null');

            $table->foreign('id_tipo_descuento')
                ->references('id_tipo_descuento')
                ->on('tipos_descuento')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_venta']);
            $table->dropForeign(['id_motivo_descuento_global']);
            $table->dropColumn([
                'id_tipo_venta', 'subtotal_bruto', 'descuento_items_total',
                'descuento_global', 'id_motivo_descuento_global', 'total_neto',
            ]);
        });

        Schema::table('ventas_detalle', function (Blueprint $table) {
            $table->dropForeign(['id_motivo_descuento']);
            $table->dropForeign(['id_tipo_descuento']);
            $table->dropColumn([
                'id_motivo_descuento', 'id_tipo_descuento', 'valor_descuento_unitario',
                'descuento_total_item', 'subtotal_final',
            ]);
        });
    }
}
