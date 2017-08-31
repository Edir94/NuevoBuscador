<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramasAVTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programasAV', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nombrePrograma');
            $table->string('diaEmision')->nullable();
            $table->time('horaEmision')->nullable();
            $table->time('duracion')->nullable();
            $table->date('fechaRegistro')->nullable();
            //$table->date('fechaActualizacion')->nullable();
            $table->integer('estado')->default(1);
            $table->integer('mediosAV_id')->unsigned();
            $table->foreign('mediosAV_id')->references('id')->on('mediosAV');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programasAV');
    }
}
