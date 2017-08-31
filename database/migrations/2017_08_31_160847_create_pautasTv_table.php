<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePautasTvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pautasTv', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('titular');
            $table->text('texto');
            $table->date('fechaPauta');
            $table->time('horaPauta');
            $table->time('duracion');
            $table->double('equivalencia');
            $table->datetime('fechaRegistro');
            //$table->datetime('fechaActualizacion');
            $table->string('tipoPauta')->default('Televisión');
            $table->integer('estado')->default(1);
            $table->integer('programasAV_id')->unsigned();
            $table->foreign('programasAV_id')->references('id')->on('programasAV');
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
        Schema::dropIfExists('pautasTv');
    }
}
