<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListaPrecioCabecerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lista_precios_cabecera', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 100);
            $table->integer('moneda_id')->unsigned();
            $table->timestamps();

            $table->foreign('moneda_id')->references('id')->on('monedas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lista_precios_detalle');
        Schema::dropIfExists('lista_precios_cabecera');
    }
}
