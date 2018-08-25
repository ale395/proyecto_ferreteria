<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosVentasCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_ventas_cab', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nro_pedido')->unique()->unsigned();
            $table->integer('cliente_id')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            $table->integer('moneda_id')->unsigned();
            $table->decimal('valor_cambio', 14, 2)->default(1);
            $table->date('fecha_emision');
            $table->decimal('monto_total', 14, 2)->default(0);
            $table->char('estado', 1);
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
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
        Schema::dropIfExists('pedidos_ventas_cab');
    }
}
