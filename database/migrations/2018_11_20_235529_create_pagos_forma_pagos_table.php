<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosFormaPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_forma_pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pagos_id');
            $table->integer('forma_pago_id');
            $table->decimal('importe_pago', 14, 2);
            $table->timestamps();
            $table->foreign('forma_pago_id')->references('id')->on('formas_pagos');
            $table->foreign('pagos_id')->references('id')->on('pagos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos_forma_pagos');
    }
}
