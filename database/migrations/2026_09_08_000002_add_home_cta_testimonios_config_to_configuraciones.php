<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $items = [
            [
                'clave' => 'home_mostrar_cta_ayuda',
                'valor' => '1',
                'descripcion' => 'Mostrar sección CTA Ayuda en el inicio',
            ],
            [
                'clave' => 'home_mostrar_testimonios',
                'valor' => '1',
                'descripcion' => 'Mostrar sección de testimonios en el inicio',
            ],
        ];

        foreach ($items as $i => $item) {
            DB::table('configuraciones')->updateOrInsert(
                ['clave' => $item['clave']],
                [
                    'categoria' => 'home',
                    'valor' => $item['valor'],
                    'descripcion' => $item['descripcion'],
                    'tipo' => 'boolean',
                    'orden' => $i + 1,
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('configuraciones')->whereIn('clave', [
            'home_mostrar_cta_ayuda',
            'home_mostrar_testimonios',
        ])->delete();
    }
};