<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedInteger('orden')->default(0)->after('id_service');
        });

        DB::table('services')
            ->orderBy('id_service')
            ->get(['id_service'])
            ->each(function ($service) {
                DB::table('services')
                    ->where('id_service', $service->id_service)
                    ->update(['orden' => $service->id_service]);
            });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('orden');
        });
    }
};
