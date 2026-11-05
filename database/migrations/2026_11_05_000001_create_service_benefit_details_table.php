<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_benefit_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_benefit_id');
            $table->text('descripcion');
            $table->timestamps();

            $table->foreign('service_benefit_id')
                ->references('id')
                ->on('service_benefits')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_benefit_details');
    }
};
