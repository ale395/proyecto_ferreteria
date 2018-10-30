<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasVentasDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas_ventas_det', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('factura_cab_id')->unsigned();
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
            $table->foreign('factura_cab_id')->references('id')->on('facturas_ventas_cab');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas_ventas_det');
    }
}
