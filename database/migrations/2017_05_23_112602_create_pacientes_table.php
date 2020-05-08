<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePacientesTable extends Migration 
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->increments('id')->index();
			$table->smallInteger('tipoDoc'); //1 - Rut ; 2 - Pasaporte
			$table->integer('rut')->unique()->nullable();
			$table->char('dv',1)->nullable();
			$table->string('numDoc')->nullable();
			$table->string('nombre');
			$table->string('apPaterno');
			$table->string('apMaterno')->nullable();
			$table->date('fechaNacimiento');
			$table->integer('genero_id')->unsigned();
			$table->integer('prevision_id')->unsigned();
			$table->integer('tramo_id')->unsigned()->nullable();
			$table->boolean('prais')->default(true);
			$table->boolean('funcionario')->default(false);
			$table->integer('via_id')->unsigned()->nullable();
			$table->string('direccion');
			$table->string('numero');
			$table->double('X', 15, 8)->nullable();
			$table->double('Y', 15, 8)->nullable();
			$table->integer('comuna_id')->unsigned();
			$table->string('telefono')->nullable();
			$table->string('telefono2')->nullable();
			$table->string('email')->nullable();
			$table->boolean('active')->default(true);
            $table->timestamps();
			
			$table->foreign('genero_id')->references('id')->on('generos')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('prevision_id')->references('id')->on('previsions')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('tramo_id')->references('id')->on('tramos')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('comuna_id')->references('id')->on('comunas')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('via_id')->references('id')->on('vias')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pacientes');
    }
}
