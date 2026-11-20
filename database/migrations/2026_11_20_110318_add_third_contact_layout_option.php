<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')->where('clave', 'contacto_tipo_formulario')->update([
            'opciones' => 'opcion_1,opcion_2,opcion_3',
        ]);
    }

    public function down(): void
    {
        DB::table('configuraciones')->where('clave', 'contacto_tipo_formulario')->update([
            'opciones' => 'opcion_1,opcion_2',
        ]);
    }
};
