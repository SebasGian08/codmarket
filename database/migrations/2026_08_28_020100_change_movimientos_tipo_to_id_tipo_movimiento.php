<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeMovimientosTipoToIdTipoMovimiento extends Migration
{
    public function up()
    {
        Schema::table('movimientos', function (Blueprint $table) {
            // nueva columna entera que guardará el id del maestro
            $table->unsignedBigInteger('id_tipo_movimiento')->nullable()->after('id_tienda');
        });

        // Mapear los valores actuales de la columna enum 'tipo' a sus ids del maestro
        $mapa = [
            'ingreso'              => 1,
            'venta'                => 2,
            'transferencia_salida' => 3,
            'transferencia_entrada'=> 4,
            'ajuste'               => 5,
        ];

        foreach ($mapa as $codigo => $id) {
            DB::table('movimientos')
                ->where('tipo', $codigo)
                ->update(['id_tipo_movimiento' => $id]);
        }

        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropColumn('tipo');

            // forzar no nulo ahora que hay datos mapeados
            $table->unsignedBigInteger('id_tipo_movimiento')->nullable(false)->change();

            $table->foreign('id_tipo_movimiento')
                ->references('id_tipo_movimiento')
                ->on('movimientos_tipo')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropForeign('movimientos_id_tipo_movimiento_foreign');

            // restore columna enum
            $table->enum('tipo', [
                'ingreso', 'venta',
                'transferencia_salida', 'transferencia_entrada', 'ajuste'
            ])->nullable()->after('id_tienda');
        });

        $mapa = [
            1 => 'ingreso',
            2 => 'venta',
            3 => 'transferencia_salida',
            4 => 'transferencia_entrada',
            5 => 'ajuste',
        ];

        foreach ($mapa as $id => $codigo) {
            DB::table('movimientos')
                ->where('id_tipo_movimiento', $id)
                ->update(['tipo' => $codigo]);
        }

        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropColumn('id_tipo_movimiento');
        });
    }
}
