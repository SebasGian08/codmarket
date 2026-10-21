<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $clave = 'footer_theme';
        $configuracion = DB::table('configuraciones')
            ->where('clave', $clave)
            ->first();

        $opciones = $configuracion
            ? array_values(array_filter(array_map('trim', explode(',', $configuracion->opciones ?? ''))))
            : [];

        foreach (['dark', 'light'] as $opcion) {
            if (!in_array($opcion, $opciones, true)) {
                $opciones[] = $opcion;
            }
        }

        $datos = [
            'categoria' => 'home',
            'descripcion' => 'Diseño del pie de página',
            'tipo' => 'select',
            'opciones' => implode(',', $opciones),
            'orden' => 2,
        ];

        if ($configuracion) {
            DB::table('configuraciones')
                ->where('clave', $clave)
                ->update($datos);
        } else {
            DB::table('configuraciones')->insert(array_merge($datos, [
                'clave' => $clave,
                'valor' => 'dark',
            ]));
        }
    }

    public function down(): void
    {
        $configuracion = DB::table('configuraciones')
            ->where('clave', 'footer_theme')
            ->first();

        if (!$configuracion) {
            return;
        }

        $opciones = array_values(array_filter(
            array_map('trim', explode(',', $configuracion->opciones ?? '')),
            static fn ($opcion) => $opcion !== 'light'
        ));

        DB::table('configuraciones')
            ->where('clave', 'footer_theme')
            ->update([
                'opciones' => implode(',', $opciones),
                'valor' => $configuracion->valor === 'light' ? 'dark' : $configuracion->valor,
            ]);
    }
};
