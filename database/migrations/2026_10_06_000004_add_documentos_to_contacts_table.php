<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentosToContactsTable extends Migration
{
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'id_documento_tipo')) {
                $table->unsignedBigInteger('id_documento_tipo')->nullable()->after('apellidos');
            }
            if (!Schema::hasColumn('contacts', 'razon_social')) {
                $table->string('razon_social', 200)->nullable()->after('id_documento_tipo');
            }
            if (!Schema::hasColumn('contacts', 'documento_numero')) {
                $table->string('documento_numero', 20)->nullable()->after('razon_social');
            }
        });
    }

    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn([
                'id_documento_tipo',
                'razon_social',
                'documento_numero',
            ]);
        });
    }
}
