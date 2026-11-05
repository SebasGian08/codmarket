<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_benefits', function (Blueprint $table) {
            $table->string('imagen')->nullable()->after('icono');
        });
    }

    public function down(): void
    {
        Schema::table('service_benefits', function (Blueprint $table) {
            $table->dropColumn('imagen');
        });
    }
};
