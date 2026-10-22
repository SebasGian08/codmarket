<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('contacts', 'id_marca')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->bigInteger('id_marca')->nullable()->after('id_service');
            });
        }

        DB::statement('ALTER TABLE contacts MODIFY id_marca BIGINT NULL');

        $marcaForeignKey = DB::selectOne(
            'SELECT CONSTRAINT_NAME AS constraint_name
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND COLUMN_NAME = ?
               AND REFERENCED_TABLE_NAME IS NOT NULL',
            ['contacts', 'id_marca']
        );

        if (!$marcaForeignKey) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->foreign('id_marca')
                    ->references('id_marca')
                    ->on('marcas')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('empresa', 'ubicacion')) {
            Schema::table('empresa', function (Blueprint $table) {
                $table->text('ubicacion')->nullable()->after('direccion');
            });
        }

        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'contacto_tipo_formulario'],
            [
                'categoria' => 'contacto',
                'valor' => 'opcion_1',
                'descripcion' => 'Formulario de contacto público',
                'tipo' => 'select',
                'opciones' => 'opcion_1,opcion_2',
                'orden' => 0,
            ]
        );
    }

    public function down(): void
    {
        DB::table('configuraciones')->where('clave', 'contacto_tipo_formulario')->delete();

        if (Schema::hasColumn('contacts', 'id_marca')) {
            $marcaForeignKey = DB::selectOne(
                'SELECT CONSTRAINT_NAME AS constraint_name
                 FROM information_schema.KEY_COLUMN_USAGE
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = ?
                   AND COLUMN_NAME = ?
                   AND REFERENCED_TABLE_NAME IS NOT NULL',
                ['contacts', 'id_marca']
            );

            Schema::table('contacts', function (Blueprint $table) use ($marcaForeignKey) {
                if ($marcaForeignKey) {
                    $table->dropForeign($marcaForeignKey->constraint_name);
                }

                $table->dropColumn('id_marca');
            });
        }

        if (Schema::hasColumn('empresa', 'ubicacion')) {
            Schema::table('empresa', function (Blueprint $table) {
                $table->dropColumn('ubicacion');
            });
        }
    }
};
