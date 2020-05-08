<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEspecialidadUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especialidad_user', function (Blueprint $table) {
			$table->integer('user_id')->unsigned();
            $table->integer('especialidad_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('especialidad_id')->references('id')->on('especialidads')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'especialidad_id']);
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('especialidad_user');
    }
}
