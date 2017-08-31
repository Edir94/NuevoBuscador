<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePautasRadioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pautasRadio', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->text('titular');
            $table->text('texto');
            $table->date('fechaPauta');
            $table->time('horaPauta');
            $table->time('duracion');
            $table->double('equivalencia');
            $table->datetime('fechaRegistro');
            //$table->datetime('fechaActualizacion');
            $table->string('tipoPauta')->default('Radio');
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
        Schema::dropIfExists('pautasRadio');
    }
}
