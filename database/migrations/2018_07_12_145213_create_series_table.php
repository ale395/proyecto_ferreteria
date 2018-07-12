<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('concepto_id')->unsigned();
            $table->string('serie', 10);
            $table->integer('timbrado_id')->unsigned()->nullable();
            $table->char('estado', 1)->default('A');
            $table->timestamps();

            $table->foreign('concepto_id')->references('id')->on('conceptos');
            $table->foreign('timbrado_id')->references('id')->on('timbrados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series');
    }
}
