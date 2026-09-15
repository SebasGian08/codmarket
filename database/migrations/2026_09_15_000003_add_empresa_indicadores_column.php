<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('empresa', 'empresa_indicadores')) {
            Schema::table('empresa', function (Blueprint $table) {
                $table->longText('empresa_indicadores')->nullable();
            });
        }

        $empresa = DB::table('empresa')->first();
        if ($empresa && empty($empresa->empresa_indicadores)) {
            DB::table('empresa')->where('id_empresa', $empresa->id_empresa)->update([
                'empresa_indicadores' => json_encode([
                    ['valor' => $empresa->indicador_1_valor ?? '+10', 'titulo' => $empresa->indicador_1_titulo ?? 'Años de experiencia'],
                    ['valor' => $empresa->indicador_2_valor ?? '100%', 'titulo' => $empresa->indicador_2_titulo ?? 'Compromiso profesional'],
                    ['valor' => $empresa->indicador_3_valor ?? '360°', 'titulo' => $empresa->indicador_3_titulo ?? 'Soluciones integrales'],
                    ['valor' => $empresa->indicador_4_valor ?? 'ISO', 'titulo' => $empresa->indicador_4_titulo ?? 'Estándares internacionales'],
                ], JSON_UNESCAPED_UNICODE),
            ]);
        }
    }

    public function down()
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->dropColumn('empresa_indicadores');
        });
    }
};
