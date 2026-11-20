<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('empresa', 'numero_soporte')) {
            Schema::table('empresa', function (Blueprint $table) {
                $table->string('numero_soporte', 30)->nullable()->after('telefono');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('empresa', 'numero_soporte')) {
            Schema::table('empresa', function (Blueprint $table) {
                $table->dropColumn('numero_soporte');
            });
        }
    }
};
