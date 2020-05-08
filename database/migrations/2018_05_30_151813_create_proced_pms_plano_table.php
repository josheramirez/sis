<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedPmsPlanoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proced_pms_plano', function (Blueprint $table) {
			$table->integer('proced_pms_id')->unsigned();
            $table->integer('plano_id')->unsigned();

            $table->foreign('proced_pms_id')->references('id')->on('tipo_procedimientos_pms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('plano_id')->references('id')->on('planos')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['proced_pms_id', 'plano_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proced_pms_plano');
    }
}
