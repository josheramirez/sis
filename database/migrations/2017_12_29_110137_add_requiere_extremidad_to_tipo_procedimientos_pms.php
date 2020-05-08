<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequiereExtremidadToTipoProcedimientosPms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('tipo_procedimientos_pms', function($table) {
	   		$table->integer('requiere_extremidad')->after('requiere_plano')->default(0);
   		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('tipo_procedimientos_pms', function($table) {
			$table->dropColumn('requiere_extremidad');
        });
    }
}
