<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdVendedorToVentasTable extends Migration
{
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_vendedor')->nullable()->after('id_metodo_pago');
        });

        Schema::table('ventas', function (Blueprint $table) {
            $table->foreign('id_vendedor')
                ->references('id_vendedor')
                ->on('vendedores')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['id_vendedor']);
            $table->dropColumn('id_vendedor');
        });
    }
}
