<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'seccion_steps_tipo'],
            [
                'categoria' => 'secciones_inicio',
                'valor' => 'opcion_1',
                'descripcion' => 'Diseño de presentación de Por qué elegirnos',
                'tipo' => 'select',
                'opciones' => 'opcion_1,opcion_2',
                'orden' => 0,
            ]
        );
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'seccion_steps_tipo')
            ->delete();
    }
};
