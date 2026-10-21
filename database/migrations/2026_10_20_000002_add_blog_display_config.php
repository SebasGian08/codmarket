<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $clave = 'blog_tipo';
        $configuracion = DB::table('configuraciones')
            ->where('clave', $clave)
            ->first();

        $opciones = $configuracion
            ? array_values(array_filter(array_map('trim', explode(',', $configuracion->opciones ?? ''))))
            : [];

        foreach (['opcion_1', 'opcion_2'] as $opcion) {
            if (!in_array($opcion, $opciones, true)) {
                $opciones[] = $opcion;
            }
        }

        $datos = [
            'categoria' => 'home',
            'descripcion' => 'Diseño de presentación del blog',
            'tipo' => 'select',
            'opciones' => implode(',', $opciones),
            'orden' => 0,
        ];

        if ($configuracion) {
            DB::table('configuraciones')
                ->where('clave', $clave)
                ->update($datos);
        } else {
            DB::table('configuraciones')->insert(array_merge($datos, [
                'clave' => $clave,
                'valor' => 'opcion_1',
            ]));
        }

        if (!DB::table('configuraciones')->where('clave', 'blog_editorial_titulo')->exists()) {
            DB::table('configuraciones')->insert([
                'clave' => 'blog_editorial_titulo',
                'categoria' => 'home',
                'valor' => 'Mantente informado',
                'descripcion' => 'Título del diseño editorial del blog',
                'tipo' => 'text',
                'orden' => 1,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->whereIn('clave', ['blog_tipo', 'blog_editorial_titulo'])
            ->delete();
    }
};
