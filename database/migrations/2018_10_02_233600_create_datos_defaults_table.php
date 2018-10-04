<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatosDefaultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_default', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('moneda_nacional_id');
            $table->integer('lista_precio_id');
            $table->timestamps();

            $table->foreign('moneda_nacional_id')->references('id')->on('monedas');
            $table->foreign('lista_precio_id')->references('id')->on('lista_precios_cabecera');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datos_default');
    }
}
