<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'web_mostrar_selector_tema'],
            [
                'categoria' => 'web',
                'valor' => '0',
                'descripcion' => 'Mostrar selector de modo claro y oscuro en la web',
                'tipo' => 'boolean',
                'orden' => 1,
            ]
        );
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'web_mostrar_selector_tema')
            ->delete();
    }
};
