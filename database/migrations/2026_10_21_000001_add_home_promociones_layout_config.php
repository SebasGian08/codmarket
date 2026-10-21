<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'home_promociones_layout'],
            [
                'categoria' => 'home',
                'valor' => 'opcion_1',
                'descripcion' => 'Diseño de presentación de promociones',
                'tipo' => 'select',
                'opciones' => 'opcion_1,opcion_2',
                'orden' => 6,
            ]
        );
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'home_promociones_layout')
            ->delete();
    }
};
