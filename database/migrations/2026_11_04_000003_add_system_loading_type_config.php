<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'sistema_loading_tipo'],
            [
                'categoria' => 'sistema',
                'valor' => 'opcion_1',
                'descripcion' => 'Estilo del loading del sistema',
                'tipo' => 'select',
                'opciones' => 'opcion_1,opcion_2,opcion_3',
                'orden' => 1,
            ]
        );
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'sistema_loading_tipo')
            ->delete();
    }
};
