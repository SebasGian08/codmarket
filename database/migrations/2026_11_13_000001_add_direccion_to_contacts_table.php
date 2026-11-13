<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('contacts', 'direccion')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->string('direccion', 255)->nullable()->after('telefono');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('contacts', 'direccion')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->dropColumn('direccion');
            });
        }
    }
};
