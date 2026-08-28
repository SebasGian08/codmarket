<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $items = [
            // Rubros
            ['seccion_rubros_titulo', 'Título de la sección Rubros', 'RUBROS', 'text'],
            ['seccion_rubros_descripcion', 'Descripción de la sección Rubros', 'Descubre la variedad de rubros que tenemos para ofrecerte los mejores productos y servicios del mercado.', 'textarea'],

            // Categorías
            ['seccion_categorias_titulo', 'Título de la sección Categorías', 'CATEGORÍAS', 'text'],
            ['seccion_categorias_descripcion', 'Descripción de la sección Categorías', 'Descubre nuestras colecciones cuidadosamente organizadas para encontrar el producto ideal con una experiencia de compra rápida y sencilla.', 'textarea'],

            // Marcas
            ['seccion_marcas_titulo', 'Título de la sección Marcas', 'MARCAS', 'text'],
            ['seccion_marcas_descripcion', 'Descripción de la sección Marcas', 'Conoce las marcas que forman parte de nuestro catálogo y descubre productos de calidad respaldados por los mejores fabricantes.', 'textarea'],

            // Clientes
            ['seccion_clientes_titulo', 'Título de la sección Clientes', 'NUESTROS CLIENTES', 'text'],
            ['seccion_clientes_descripcion', 'Descripción de la sección Clientes', 'Empresas y personas que confían en nosotros.', 'textarea'],

            // Productos
            ['seccion_productos_titulo', 'Título de la sección Productos', 'NUESTROS PRODUCTOS', 'text'],
            ['seccion_productos_descripcion', 'Descripción de la sección Productos', 'Explora nuestra variedad de productos seleccionados para ti.', 'textarea'],

            // Productos destacados
            ['seccion_destacados_titulo', 'Título de la sección Productos destacados', 'Más vendidos', 'text'],

            // Servicios
            ['seccion_servicios_titulo', 'Título de la sección Servicios', 'SERVICIOS', 'text'],
            ['seccion_servicios_descripcion', 'Descripción de la sección Servicios', 'Soluciones profesionales para potenciar tu empresa.', 'textarea'],

            // Steps (Por qué elegirnos)
            ['seccion_steps_titulo', 'Título de la sección Por qué elegirnos', '¿Por qué elegirnos?', 'text'],
            ['seccion_steps_subtitulo', 'Subtítulo (h2) de la sección Por qué elegirnos', 'Experiencia premium en cada compra', 'text'],
            ['seccion_steps_descripcion', 'Descripción de la sección Por qué elegirnos', 'Diseñamos una experiencia moderna, rápida y segura para que compres con total confianza.', 'textarea'],

            // Suscripción
            ['seccion_suscripcion_titulo', 'Título pequeño de la sección Suscripción', 'BENEFICIOS EXCLUSIVOS', 'text'],
            ['seccion_suscripcion_descripcion', 'Mensaje principal de la sección Suscripción', 'Hasta 35% de descuento y ofertas exclusivas en tu correo.', 'text'],

            // Testimonios
            ['seccion_testimonios_titulo', 'Título de la sección Testimonios / Casos de éxito', 'CASOS DE ÉXITO', 'text'],
            ['seccion_testimonios_descripcion', 'Descripción de la sección Testimonios', 'Algunos de nuestros proyectos realizados.', 'textarea'],

            // Preguntas frecuentes
            ['seccion_preguntas_titulo', 'Título de la sección Preguntas frecuentes', 'PREGUNTAS', 'text'],
            ['seccion_preguntas_descripcion', 'Descripción de la sección Preguntas frecuentes', 'Resolvemos las dudas más comunes sobre nuestros servicios.', 'textarea'],

            // Blog
            ['seccion_blog_titulo', 'Título de la sección Blog', 'Últimos artículos', 'text'],
            ['seccion_blog_descripcion', 'Descripción de la sección Blog', 'Explora nuestras novedades', 'textarea'],
        ];

        $categoria = 'secciones_inicio';

        foreach ($items as $i => [$clave, $descripcion, $valor, $tipo]) {
            DB::table('configuraciones')->updateOrInsert(
                ['clave' => $clave],
                [
                    'categoria' => $categoria,
                    'valor' => $valor,
                    'descripcion' => $descripcion,
                    'tipo' => $tipo,
                    'orden' => $i + 1,
                ]
            );
        }
    }

    public function down(): void
    {
        $claves = [
            'seccion_rubros_titulo', 'seccion_rubros_descripcion',
            'seccion_categorias_titulo', 'seccion_categorias_descripcion',
            'seccion_marcas_titulo', 'seccion_marcas_descripcion',
            'seccion_clientes_titulo', 'seccion_clientes_descripcion',
            'seccion_productos_titulo', 'seccion_productos_descripcion',
            'seccion_destacados_titulo',
            'seccion_servicios_titulo', 'seccion_servicios_descripcion',
            'seccion_steps_titulo', 'seccion_steps_subtitulo', 'seccion_steps_descripcion',
            'seccion_suscripcion_titulo', 'seccion_suscripcion_descripcion',
            'seccion_testimonios_titulo', 'seccion_testimonios_descripcion',
            'seccion_preguntas_titulo', 'seccion_preguntas_descripcion',
            'seccion_blog_titulo', 'seccion_blog_descripcion',
        ];

        DB::table('configuraciones')->whereIn('clave', $claves)->delete();
    }
};
