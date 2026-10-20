<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $secciones = [
            'rubros' => 'Rubros',
            'categorias' => 'Categorías',
            'marcas' => 'Marcas',
            'clientes' => 'Clientes',
            'productos' => 'Productos',
            'destacados' => 'Productos destacados',
            'servicios' => 'Servicios',
            'steps' => 'Por qué elegirnos',
            'suscripcion' => 'Suscripción',
            'testimonios' => 'Testimonios / Casos de éxito',
            'preguntas' => 'Preguntas frecuentes',
            'blog' => 'Blog',
        ];

        $orden = 30;
        foreach ($secciones as $seccion => $nombre) {
            DB::table('configuraciones')->updateOrInsert(
                ['clave' => "seccion_{$seccion}_mostrar_encabezado"],
                [
                    'categoria' => 'secciones_inicio',
                    'valor' => '1',
                    'descripcion' => "Mostrar encabezado de {$nombre}",
                    'tipo' => 'boolean',
                    'orden' => $orden++,
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->whereIn('clave', [
                'seccion_rubros_mostrar_encabezado',
                'seccion_categorias_mostrar_encabezado',
                'seccion_marcas_mostrar_encabezado',
                'seccion_clientes_mostrar_encabezado',
                'seccion_productos_mostrar_encabezado',
                'seccion_destacados_mostrar_encabezado',
                'seccion_servicios_mostrar_encabezado',
                'seccion_steps_mostrar_encabezado',
                'seccion_suscripcion_mostrar_encabezado',
                'seccion_testimonios_mostrar_encabezado',
                'seccion_preguntas_mostrar_encabezado',
                'seccion_blog_mostrar_encabezado',
            ])
            ->delete();
    }
};
