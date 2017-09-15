<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediosInternetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mediosInternet', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nombreMedio');
            $table->string('rutaMedio',100)->nullable();
            $table->date('fechaRegistro')->nullable();
            //$table->date('fechaActualizacion')->nullable();
            $table->integer('estado')->default(1);
            $table->integer('tipoMedios_id')->unsigned();
            $table->foreign('tipoMedios_id')->references('id')->on('tipoMedios');
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
        Schema::dropIfExists('mediosInternet');
    }
}
