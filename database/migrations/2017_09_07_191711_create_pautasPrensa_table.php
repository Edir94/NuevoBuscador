<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePautasPrensaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pautasPrensa', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('titular');
            $table->string('texto');
            $table->date('fechaPauta');
            $table->string('autor')->nullable();
            $table->datetime('fechaRegistro');
            $table->string('tipoPauta')->default('Prensa');
            $table->integer('estado')->default(1);
            $table->integer('seccionesPrensa_id')->unsigned();
            $table->foreign('seccionesPrensa_id')->references('id')->on('seccionesPrensa');
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
        Schema::dropIfExists('pautasPrensa');
    }
}
