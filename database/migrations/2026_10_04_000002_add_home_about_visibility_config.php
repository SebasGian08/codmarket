<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'home_mostrar_nosotros'],
            [
                'categoria' => 'home',
                'valor' => '1',
                'descripcion' => 'Mostrar sección Nosotros en el inicio',
                'tipo' => 'boolean',
                'orden' => 4,
            ]
        );
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'home_mostrar_nosotros')
            ->delete();
    }
};
