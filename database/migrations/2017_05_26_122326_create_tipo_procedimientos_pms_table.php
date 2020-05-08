<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoProcedimientosPmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_procedimientos_pms', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('name');
            $table->integer('tipo_procedimiento_id')->unsigned();
            $table->string('prestamin');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(array('name', 'tipo_procedimiento_id'));
            $table->foreign('tipo_procedimiento_id')->references('id')->on('tipo_procedimientos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_procedimientos_pms');
    }
}