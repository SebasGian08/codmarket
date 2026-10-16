<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDocumentosTipoTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('documentos_tipo')) {
            Schema::create('documentos_tipo', function (Blueprint $table) {
                $table->bigIncrements('id_documento_tipo');
                $table->string('nombre', 100);
                $table->string('codigo', 20)->nullable();
                $table->integer('longitud')->default(0);
                $table->boolean('estado')->default(1);
                $table->timestamps();
            });
        }

        // Tipos base: DNI y RUC
        DB::table('documentos_tipo')->updateOrInsert(
            ['codigo' => 'DNI'],
            ['nombre' => 'DNI', 'longitud' => 8, 'estado' => 1]
        );
        DB::table('documentos_tipo')->updateOrInsert(
            ['codigo' => 'RUC'],
            ['nombre' => 'RUC', 'longitud' => 11, 'estado' => 1]
        );
    }

    public function down()
    {
        Schema::dropIfExists('documentos_tipo');
    }
}
