<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumeracionSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numeracion_series', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('concepto_id')->unsigned();
            $table->integer('serie_id')->unsigned();
            $table->integer('nro_inicial')->unsigned();
            $table->integer('nro_final')->unsigned();
            $table->char('estado', 1)->default('A');
            $table->timestamps();

            $table->foreign('concepto_id')->references('id')->on('conceptos');
            $table->foreign('serie_id')->references('id')->on('series');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('numeracion_series');
    }
}
