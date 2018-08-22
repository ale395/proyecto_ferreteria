<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesVendedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series_vendedores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('serie_id');
            $table->integer('vendedor_id');
            $table->timestamps();

            $table->foreign('serie_id')->references('id')->on('series');
            $table->foreign('vendedor_id')->references('id')->on('vendedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series_vendedores');
    }
}
