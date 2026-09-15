<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('empresa', 'indicador_1_valor')) {
            Schema::table('empresa', function (Blueprint $table) {
                $table->string('indicador_1_valor', 50)->default('+10');
                $table->string('indicador_1_titulo', 150)->default('Años de experiencia');
                $table->string('indicador_2_valor', 50)->default('100%');
                $table->string('indicador_2_titulo', 150)->default('Compromiso profesional');
                $table->string('indicador_3_valor', 50)->default('360°');
                $table->string('indicador_3_titulo', 150)->default('Soluciones integrales');
                $table->string('indicador_4_valor', 50)->default('ISO');
                $table->string('indicador_4_titulo', 150)->default('Estándares internacionales');
            });
        }
    }

    public function down()
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->dropColumn([
                'indicador_1_valor', 'indicador_1_titulo',
                'indicador_2_valor', 'indicador_2_titulo',
                'indicador_3_valor', 'indicador_3_titulo',
                'indicador_4_valor', 'indicador_4_titulo',
            ]);
        });
    }
};
