<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdVendedorToCajasTable extends Migration
{
    public function up()
    {
        Schema::table('cajas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_vendedor')->nullable()->after('id_usuario');
        });

        Schema::table('cajas', function (Blueprint $table) {
            $table->foreign('id_vendedor')
                ->references('id_vendedor')
                ->on('vendedores')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('cajas', function (Blueprint $table) {
            $table->dropForeign(['id_vendedor']);
            $table->dropColumn('id_vendedor');
        });
    }
}
