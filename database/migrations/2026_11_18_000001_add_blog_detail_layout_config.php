<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'blog_detalle_tipo'],
            [
                'categoria' => 'blog',
                'valor' => 'opcion_1',
                'descripcion' => 'Diseño de presentación del detalle del blog',
                'tipo' => 'select',
                'opciones' => 'opcion_1,opcion_2',
                'orden' => 1,
            ]
        );
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'blog_detalle_tipo')
            ->delete();
    }
};
