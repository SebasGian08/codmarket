<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $keys = [
        'seccion_nosotros_tipo',
        'seccion_servicios_tipo',
        'seccion_steps_tipo',
    ];

    public function up(): void
    {
        DB::table('configuraciones')
            ->whereIn('clave', $this->keys)
            ->update(['categoria' => 'home']);
    }

    public function down(): void
    {
        DB::table('configuraciones')
            ->whereIn('clave', $this->keys)
            ->update(['categoria' => 'secciones_inicio']);
    }
};
