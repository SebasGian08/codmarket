<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuditoriaToUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            if (!Schema::hasColumn('usuarios', 'created_by')) $table->unsignedBigInteger('created_by')->nullable();
            if (!Schema::hasColumn('usuarios', 'updated_by')) $table->unsignedBigInteger('updated_by')->nullable();
            if (!Schema::hasColumn('usuarios', 'deleted_by')) $table->unsignedBigInteger('deleted_by')->nullable();
            
            // Columna vital para el Soft Delete
            if (!Schema::hasColumn('usuarios', 'deleted_at')) $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            //
        });
    }
}
