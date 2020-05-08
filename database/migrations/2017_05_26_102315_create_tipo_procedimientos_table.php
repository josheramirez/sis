<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoProcedimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_procedimientos', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('name');
            $table->integer('tipo_prestacion_id')->unsigned();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(array('name', 'tipo_prestacion_id'));
            $table->foreign('tipo_prestacion_id')->references('id')->on('tipo_prestacions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_procedimientos');
    }
}