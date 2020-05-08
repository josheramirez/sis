<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListaEsperasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lista_esperas', function (Blueprint $table) {

			$table->increments('id')->index();
            $table->text('precdiag')->nullable();
            $table->date('fechacitacion')->nullable();
            $table->integer('run_medico_solicita')->nullable();
            $table->char('dv_medico_solicita',1)->nullable();
            $table->date('fechamodif')->nullable();
            $table->date('fechaegreso')->nullable();
            $table->date('fechaingreso')->nullable();
            $table->date('fechacontrol')->nullable();
            $table->date('fechaexamen')->nullable();
            $table->integer('nodo')->unsigned();
            $table->string('prestamin_ing');
            $table->string('prestamin_egr')->nullable();
            $table->integer('tipo_ges_id')->unsigned();
            $table->integer('establecimientos_id_origen')->unsigned();
            $table->integer('establecimientos_id_destino')->unsigned();
            $table->integer('establecimientos_id_resuelve')->unsigned()->nullable();
            $table->integer('pacientes_id')->unsigned();
            $table->integer('causal_egresos_id')->unsigned()->nullable();
            $table->integer('cie10s_id')->unsigned();
            $table->integer('especialidads_ingreso_id')->unsigned();
            $table->integer('especialidads_egreso_id')->unsigned()->nullable();
            $table->integer('extremidads_id')->unsigned()->nullable();
            $table->integer('motivo_solicituds_id')->unsigned()->nullable();
            $table->integer('planos_id')->unsigned()->nullable();
            $table->integer('nivels_id')->unsigned()->nullable();
            $table->integer('tipo_consultas_id')->unsigned();
            $table->integer('tipo_esperas_id')->unsigned()->nullable();
            $table->integer('tipo_prestacions_id')->unsigned();
			$table->integer('tipo_procedimientos_id')->unsigned()->nullable();
			$table->integer('tipo_procedimientos_pms_id')->unsigned()->nullable();
            $table->integer('tipo_salidas_id')->unsigned()->nullable();
            $table->integer('tramos_id')->unsigned()->nullable();
            $table->integer('users_id_ingreso')->unsigned();
            $table->integer('users_id_egreso')->unsigned()->nullable();
            $table->boolean('active')->default(true);
			$table->timestamps();


            $table->foreign('nodo')->references('id')->on('establecimientos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('establecimientos_id_origen')->references('id')->on('establecimientos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('establecimientos_id_destino')->references('id')->on('establecimientos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('establecimientos_id_resuelve')->references('id')->on('establecimientos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pacientes_id')->references('id')->on('pacientes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('causal_egresos_id')->references('id')->on('causal_egresos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('cie10s_id')->references('id')->on('cie10s')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('especialidads_ingreso_id')->references('id')->on('especialidads')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('especialidads_egreso_id')->references('id')->on('especialidads')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('extremidads_id')->references('id')->on('extremidads')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('motivo_solicituds_id')->references('id')->on('motivo_solicituds')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('planos_id')->references('id')->on('planos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('nivels_id')->references('id')->on('nivels')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tipo_esperas_id')->references('id')->on('tipo_esperas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tipo_prestacions_id')->references('id')->on('tipo_prestacions')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('tipo_procedimientos_id')->references('id')->on('tipo_procedimientos')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('tipo_procedimientos_pms_id')->references('id')->on('tipo_procedimientos_pms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tipo_salidas_id')->references('id')->on('tipo_salidas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tramos_id')->references('id')->on('tramos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('users_id_ingreso')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('users_id_egreso')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lista_esperas');
    }
}
