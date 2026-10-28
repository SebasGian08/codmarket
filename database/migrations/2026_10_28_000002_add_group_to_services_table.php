<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('services') && !Schema::hasColumn('services', 'grupo')) {
            Schema::table('services', function (Blueprint $table) {
                $table->string('grupo', 100)->nullable()->after('nombre');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('services') && Schema::hasColumn('services', 'grupo')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('grupo');
            });
        }
    }
};
