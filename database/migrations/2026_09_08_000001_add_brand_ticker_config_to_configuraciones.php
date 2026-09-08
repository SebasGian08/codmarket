<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'home_mostrar_brand_ticker'],
            [
                'categoria' => 'home',
                'valor' => '1',
                'descripcion' => 'Mostrar barra animada de la marca (brand ticker)',
                'tipo' => 'boolean',
                'orden' => 1,
            ]
        );
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'home_mostrar_brand_ticker')
            ->delete();
    }
};