<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'home_mostrar_clientes'],
            [
                'categoria' => 'home',
                'valor' => '0',
                'descripcion' => 'Mostrar sección de clientes (carrusel) en el inicio',
                'tipo' => 'boolean',
                'orden' => 0,
            ]
        );
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'home_mostrar_clientes')
            ->delete();
    }
};
