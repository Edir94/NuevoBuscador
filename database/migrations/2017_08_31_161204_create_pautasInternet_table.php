<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePautasInternetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pautasInternet', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->text('titular');
            $table->text('texto');
            $table->date('fechaPauta');
            $table->string('rutaImagen');
            $table->double('equivalencia');
            $table->datetime('fechaRegistro')->nullable();
            //$table->datetime('fechaActualizacion');
            $table->string('tipoPauta')->default('Internet');
            $table->integer('estado')->default(1);
            $table->integer('mediosInternet_id')->unsigned();
            $table->foreign('mediosInternet_id')->references('id')->on('mediosInternet');
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
        Schema::dropIfExists('pautasInternet');
    }
}
