<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $clave = 'seccion_steps_tipo';
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
            'descripcion' => 'Diseño de presentación de Por qué elegirnos',
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
        DB::table('configuraciones')
            ->where('clave', 'seccion_steps_tipo')
            ->update([
                'opciones' => 'opcion_1,opcion_2',
                'valor' => DB::raw("CASE WHEN valor = 'opcion_3' THEN 'opcion_1' ELSE valor END"),
            ]);
    }
};
