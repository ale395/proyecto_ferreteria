<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosVentasDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_ventas_det', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pedido_cab_id')->unsigned();
            $table->integer('articulo_id')->unsigned();
            $table->decimal('cantidad', 14, 2);
            $table->decimal('precio_unitario', 14, 2);
            $table->integer('porcentaje_descuento')->unsigned();
            $table->decimal('monto_descuento', 14, 2);
            $table->integer('porcentaje_iva')->unsigned();
            $table->decimal('monto_exenta', 14, 2);
            $table->decimal('monto_gravada', 14, 2);
            $table->decimal('monto_iva', 14, 2);
            $table->decimal('monto_total', 14, 2);
            $table->timestamps();

            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->foreign('pedido_cab_id')->references('id')->on('pedidos_ventas_cab');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos_ventas_det');
    }
}
