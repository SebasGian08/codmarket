<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $config = DB::table('configuraciones')
            ->where('clave', 'home_banner_mostrar_formulario')
            ->first();

        if ($config) {
            DB::table('configuraciones')
                ->where('clave', 'home_banner_mostrar_formulario')
                ->update(['categoria' => 'contacto']);

            return;
        }

        DB::table('configuraciones')->insert([
            'clave' => 'home_banner_mostrar_formulario',
            'categoria' => 'contacto',
            'valor' => '1',
            'descripcion' => 'Mostrar formulario de contacto en el banner',
            'tipo' => 'boolean',
            'orden' => 5,
        ]);
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'home_banner_mostrar_formulario')
            ->update(['categoria' => 'home']);
    }
};
