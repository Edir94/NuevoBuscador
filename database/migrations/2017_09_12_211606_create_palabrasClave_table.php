<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePalabrasClaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('palabrasClave', function (Blueprint $table) {
            $table->increments('id');
            $table->string('palabraClave');
            $table->integer('temas_id')->unsigned();
            $table->foreign('temas_id')->references('id')->on('temas');

           // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('palabrasClave');
    }
}
