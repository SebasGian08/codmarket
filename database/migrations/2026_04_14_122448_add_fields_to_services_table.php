<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->longText('content')->nullable();
            $table->string('portada')->nullable();
            $table->string('imagen_referencial')->nullable();
        });
    }

   /*  public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'content',
                'portada',
                'imagen_referencial'
            ]);
        });
    } */

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            //
        });
    }
}
