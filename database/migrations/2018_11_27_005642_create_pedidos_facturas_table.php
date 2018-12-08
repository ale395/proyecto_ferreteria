<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('factura_cabecera_id')->unsigned();
            $table->integer('pedido_cabecera_id')->unsigned();
            $table->timestamps();

            $table->foreign('factura_cabecera_id')->references('id')->on('facturas_ventas_cab');
            $table->foreign('pedido_cabecera_id')->references('id')->on('pedidos_ventas_cab');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos_facturas');
    }
}
