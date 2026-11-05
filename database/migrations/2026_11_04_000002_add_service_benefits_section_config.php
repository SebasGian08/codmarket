<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $configuraciones = [
        [
            'clave' => 'seccion_servicio_beneficios_titulo',
            'valor' => 'Beneficios que hacen la diferencia',
            'descripcion' => 'Título de la sección de beneficios del servicio',
            'tipo' => 'text',
            'opciones' => null,
            'orden' => 1,
        ],
        [
            'clave' => 'seccion_servicio_beneficios_descripcion',
            'valor' => 'Obtén una solución completa pensada para ofrecerte mejores resultados y una experiencia profesional.',
            'descripcion' => 'Descripción de la sección de beneficios del servicio',
            'tipo' => 'textarea',
            'opciones' => null,
            'orden' => 2,
        ],
        [
            'clave' => 'seccion_servicio_tipo',
            'valor' => 'opcion_1',
            'descripcion' => 'Diseño de presentación de beneficios del servicio',
            'tipo' => 'select',
            'opciones' => 'opcion_1',
            'orden' => 3,
        ],
    ];

    public function up(): void
    {
        foreach ($this->configuraciones as $configuracion) {
            DB::table('configuraciones')->updateOrInsert(
                ['clave' => $configuracion['clave']],
                array_merge($configuracion, ['categoria' => 'seccion_servicio'])
            );
        }
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->whereIn('clave', array_column($this->configuraciones, 'clave'))
            ->delete();
    }
};
