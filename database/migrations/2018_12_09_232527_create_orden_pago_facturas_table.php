<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenPagoFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_pago_facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('orden_pago_id');
            $table->integer('compras_id');
            $table->decimal('importe_afectado', 14, 2);
            $table->timestamps();

            $table->foreign('orden_pago_id')->references('id')->on('orden_pago');
            $table->foreign('compras_id')->references('id')->on('compras_cab');     
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orden_pago_facturas');
    }
}
