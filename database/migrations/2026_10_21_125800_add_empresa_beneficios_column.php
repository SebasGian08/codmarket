<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('empresa', 'empresa_beneficios')) {
            Schema::table('empresa', function (Blueprint $table) {
                $table->longText('empresa_beneficios')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('empresa', 'empresa_beneficios')) {
            Schema::table('empresa', function (Blueprint $table) {
                $table->dropColumn('empresa_beneficios');
            });
        }
    }
};
