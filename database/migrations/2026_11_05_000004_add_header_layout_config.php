<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $clave = 'header_tipo';
        $configuracion = DB::table('configuraciones')->where('clave', $clave)->first();

        if ($configuracion) {
            $opciones = array_filter(array_map('trim', explode(',', $configuracion->opciones ?? '')));
            foreach (['opcion_1', 'opcion_2'] as $opcion) {
                if (!in_array($opcion, $opciones, true)) {
                    $opciones[] = $opcion;
                }
            }

            DB::table('configuraciones')
                ->where('clave', $clave)
                ->update([
                    'categoria' => 'header',
                    'descripcion' => 'Diseño del encabezado del sitio',
                    'tipo' => 'select',
                    'opciones' => implode(',', $opciones),
                ]);

            return;
        }

        DB::table('configuraciones')->insert([
            'categoria' => 'header',
            'clave' => $clave,
            'valor' => 'opcion_1',
            'descripcion' => 'Diseño del encabezado del sitio',
            'tipo' => 'select',
            'opciones' => 'opcion_1,opcion_2',
            'orden' => 0,
        ]);
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'header_tipo')
            ->delete();
    }
};
