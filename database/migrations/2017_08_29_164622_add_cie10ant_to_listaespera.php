<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCie10antToListaespera extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('lista_esperas', function($table) {
            $table->string('cie10_ant')->after('users_id_egreso')->nullable();;
            $table->integer('id_lep')->after('cie10_ant')->unsigned()->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lista_esperas', function($table) {
            $table->dropColumn('cie10_ant');
            $table->dropColumn('id_lep');
        });
    }
}
