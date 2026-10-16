<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Elimina de `configuraciones` los grupos que ahora se gestionan
     * desde el módulo Empresa:
     *  - CONTACTO: WhatsApp global, Correo principal, Dirección empresa
     *  - REDES: Facebook, Instagram, TikTok
     */
    public function up(): void
    {
        DB::table('configuraciones')
            ->whereRaw('LOWER(categoria) IN (?, ?)', ['contacto', 'redes'])
            ->delete();
    }

    public function down(): void
    {
        // No se restauran: estos datos ahora provienen de la tabla `empresa`.
    }
};
