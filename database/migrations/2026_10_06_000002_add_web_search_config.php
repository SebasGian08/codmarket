<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'web_mostrar_buscador'],
            [
                'categoria' => 'web',
                'valor' => '1',
                'descripcion' => 'Mostrar el buscador en la cabecera de la web',
                'tipo' => 'boolean',
                'orden' => 2,
            ]
        );
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'web_mostrar_buscador')
            ->delete();
    }
};
