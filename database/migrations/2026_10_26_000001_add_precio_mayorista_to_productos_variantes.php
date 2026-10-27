<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('productos_variantes') && !Schema::hasColumn('productos_variantes', 'precio_mayorista')) {
            Schema::table('productos_variantes', function (Blueprint $table) {
                $table->decimal('precio_mayorista', 10, 2)->nullable()->after('precio_oferta');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('productos_variantes') && Schema::hasColumn('productos_variantes', 'precio_mayorista')) {
            Schema::table('productos_variantes', function (Blueprint $table) {
                $table->dropColumn('precio_mayorista');
            });
        }
    }
};
