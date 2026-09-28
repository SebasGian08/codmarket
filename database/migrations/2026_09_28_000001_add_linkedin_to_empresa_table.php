<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('empresa', 'linkedin')) {
            Schema::table('empresa', function (Blueprint $table) {
                $table->string('linkedin', 255)->nullable()->after('tiktok');
            });
        }
    }

    public function down()
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->dropColumn('linkedin');
        });
    }
};