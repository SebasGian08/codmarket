<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('promociones') && !Schema::hasColumn('promociones', 'posicion_boton')) {
            Schema::table('promociones', function (Blueprint $table) {
                $table->string('posicion_boton', 20)->default('centro');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('promociones') && Schema::hasColumn('promociones', 'posicion_boton')) {
            Schema::table('promociones', function (Blueprint $table) {
                $table->dropColumn('posicion_boton');
            });
        }
    }
};
