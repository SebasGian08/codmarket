<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Agrega a `configuraciones` los campos de integraciones SEO:
     * verificación de Google Search Console, Bing Webmaster,
     * Google Analytics 4 y Google Tag Manager.
     *
     * Se usa la misma categoría que ya tienen las claves SEO existentes
     * (se detecta por `seo_title`) para que aparezcan en el mismo tab.
     */
    public function up(): void
    {
        $categoria = DB::table('configuraciones')
            ->where('clave', 'seo_title')
            ->value('categoria') ?: 'seo';

        $items = [
            ['google_site_verification', 'Verificación de Google Search Console (meta google-site-verification)', 10],
            ['bing_site_verification', 'Verificación de Bing Webmaster (meta msvalidate.01)', 11],
            ['google_analytics_id', 'ID de Google Analytics 4 (ej: G-XXXXXXXXXX)', 12],
            ['google_tag_manager_id', 'ID de Google Tag Manager (ej: GTM-XXXXXXX)', 13],
        ];

        foreach ($items as [$clave, $descripcion, $orden]) {
            DB::table('configuraciones')->updateOrInsert(
                ['clave' => $clave],
                [
                    'categoria'   => $categoria,
                    'valor'       => '',
                    'descripcion' => $descripcion,
                    'tipo'        => 'text',
                    'orden'       => $orden,
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->whereIn('clave', [
                'google_site_verification',
                'bing_site_verification',
                'google_analytics_id',
                'google_tag_manager_id',
            ])
            ->delete();
    }
};
