<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMapaDerivacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapa_derivacions', function (Blueprint $table) {
            $table->increments('id')->index();
			$table->integer('especialidad_id')->unsigned();
			$table->integer('etario_id')->unsigned();
			$table->integer('contraref_id')->unsigned();
			$table->integer('origen_id')->unsigned();
			$table->boolean('active')->default(true);
            $table->timestamps();
			
			$table->unique(array('especialidad_id', 'etario_id', 'contraref_id', 'origen_id'),'mapa_unique');
			
			$table->foreign('especialidad_id')->references('id')->on('especialidads')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('etario_id')->references('id')->on('etarios')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('contraref_id')->references('id')->on('establecimientos')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('origen_id')->references('id')->on('establecimientos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mapa_derivacions');
    }
}
