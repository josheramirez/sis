<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFechaDigitacionEgresoToListaespera extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('lista_esperas', function($table) {
            $table->date('fecha_digitacion_egreso')->after('users_id_egreso')->nullable();
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
            $table->date('fecha_digitacion_egreso');
        });
    }
}
