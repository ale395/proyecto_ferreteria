<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasFormaPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras_forma_pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compras_cab_id')->unsigned();
            $table->integer('forma_pago_id')->unsigned();
            $table->integer('moneda_id')->unsigned();
            $table->decimal('valor_cambio', 14, 2)->default(1);
            $table->decimal('importe', 14, 2)->default(0);
            $table->timestamps();

            $table->foreign('compras_cab_id')->references('id')->on('compras_cab');
            $table->foreign('forma_pago_id')->references('id')->on('formas_pagos');
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
        Schema::dropIfExists('compras_forma_pagos');
    }
}
