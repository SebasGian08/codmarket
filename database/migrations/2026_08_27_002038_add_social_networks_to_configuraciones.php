<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $socials = [
            ['youtube_url', 'URL de YouTube', 6],
            ['twitter_url', 'URL de Twitter / X', 7],
            ['linkedin_url', 'URL de LinkedIn', 8],
        ];

        foreach ($socials as [$clave, $desc, $orden]) {
            DB::table('configuraciones')->updateOrInsert(
                ['clave' => $clave],
                [
                    'categoria' => 'redes_sociales',
                    'valor' => '',
                    'descripcion' => $desc,
                    'tipo' => 'text',
                    'orden' => $orden,
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->whereIn('clave', ['youtube_url', 'twitter_url', 'linkedin_url'])
            ->delete();
    }
};
