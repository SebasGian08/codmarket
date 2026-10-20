<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $clave = 'categorias_tipo';
        $configuracion = DB::table('configuraciones')
            ->where('clave', $clave)
            ->first();

        $opciones = $configuracion
            ? array_values(array_filter(array_map('trim', explode(',', $configuracion->opciones ?? ''))))
            : [];

        foreach (['opcion_1', 'opcion_2', 'opcion_3'] as $opcion) {
            if (!in_array($opcion, $opciones, true)) {
                $opciones[] = $opcion;
            }
        }

        $datos = [
            'categoria' => 'home',
            'descripcion' => 'Diseño de presentación de categorías',
            'tipo' => 'select',
            'opciones' => implode(',', $opciones),
            'orden' => 0,
        ];

        if ($configuracion) {
            DB::table('configuraciones')
                ->where('clave', $clave)
                ->update($datos);

            return;
        }

        DB::table('configuraciones')->insert(array_merge($datos, [
            'clave' => $clave,
            'valor' => 'opcion_1',
        ]));
    }

    public function down(): void
    {
        $configuracion = DB::table('configuraciones')
            ->where('clave', 'categorias_tipo')
            ->first();

        if (!$configuracion) {
            return;
        }

        $opciones = array_filter(
            array_map('trim', explode(',', $configuracion->opciones ?? '')),
            static fn ($opcion) => $opcion !== 'opcion_3'
        );

        DB::table('configuraciones')
            ->where('clave', 'categorias_tipo')
            ->update(['opciones' => implode(',', $opciones)]);
    }
};
