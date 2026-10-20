<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'home_mostrar_indicadores'],
            [
                'categoria' => 'home',
                'valor' => '1',
                'descripcion' => 'Mostrar sección de indicadores en el inicio',
                'tipo' => 'boolean',
                'orden' => 5,
            ]
        );
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'home_mostrar_indicadores')
            ->delete();
    }
};
