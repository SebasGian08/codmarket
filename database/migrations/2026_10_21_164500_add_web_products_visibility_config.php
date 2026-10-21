<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'web_mostrar_productos'],
            [
                'categoria' => 'web',
                'valor' => '1',
                'descripcion' => 'Mostrar Productos en el menú de la cabecera',
                'tipo' => 'boolean',
                'orden' => 3,
            ]
        );
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'web_mostrar_productos')
            ->delete();
    }
};
