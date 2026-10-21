<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $configuracion = DB::table('configuraciones')
            ->where('clave', 'home_promociones_layout')
            ->first();

        if (!$configuracion) {
            DB::table('configuraciones')->insert([
                'clave' => 'home_promociones_layout',
                'categoria' => 'home',
                'valor' => 'opcion_1',
                'descripcion' => 'Diseño de presentación de promociones',
                'tipo' => 'select',
                'opciones' => 'opcion_1,opcion_2',
                'orden' => 6,
            ]);

            return;
        }

        $valor = [
            'mosaico_actual' => 'opcion_1',
            'banner_ancho' => 'opcion_2',
        ][$configuracion->valor] ?? $configuracion->valor;

        DB::table('configuraciones')
            ->where('clave', 'home_promociones_layout')
            ->update([
                'valor' => in_array($valor, ['opcion_1', 'opcion_2'], true) ? $valor : 'opcion_1',
                'descripcion' => 'Diseño de presentación de promociones',
                'tipo' => 'select',
                'opciones' => 'opcion_1,opcion_2',
                'orden' => 6,
            ]);
    }

    public function down(): void
    {
        $configuracion = DB::table('configuraciones')
            ->where('clave', 'home_promociones_layout')
            ->first();

        if (!$configuracion) {
            return;
        }

        $valor = [
            'opcion_1' => 'mosaico_actual',
            'opcion_2' => 'banner_ancho',
        ][$configuracion->valor] ?? 'mosaico_actual';

        DB::table('configuraciones')
            ->where('clave', 'home_promociones_layout')
            ->update([
                'valor' => $valor,
                'descripcion' => 'Diseño de promociones (la opción de banner ancho muestra solo la primera)',
                'tipo' => 'select',
                'opciones' => 'mosaico_actual,banner_ancho',
            ]);
    }
};
