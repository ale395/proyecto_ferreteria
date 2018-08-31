<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotizacionesTable extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->increments('id');

            $table->date('fecha')->unsigned();
            $table->integer('moneda_id')->unsigned();
            $table->decimal('valor_compra', 14, 4);
            $table->decimal('valor_venta', 14, 4);

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
        Schema::dropIfExists('cotizaciones');
    }
}
