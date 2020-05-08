<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMedicoresolToListaespera extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lista_esperas', function($table) {
            $table->integer('run_medico_resol')->after('tramos_id')->nullable();
			$table->char('dv_medico_resol',1)->after('run_medico_resol')->nullable();
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
            $table->dropColumn('run_medico_resol');
			$table->dropColumn('dv_medico_resol');
        });
    }
}
