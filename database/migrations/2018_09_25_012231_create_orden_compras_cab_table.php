<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenComprasCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_compras_cab', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nro_orden')->unique()->unsigned();
            $table->integer('proveedor_id')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            $table->integer('moneda_id')->unsigned();
            $table->decimal('valor_cambio', 14, 2)->default(1);
            $table->date('fecha_emision');
            $table->decimal('monto_total', 14, 2)->default(0);
            $table->char('estado', 1); //A-ACEPTADO; P-COMPRADO; C-CANCELADO
            $table->timestamps();

            $table->foreign('proveedor_id')->references('id')->on('proveedores');
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
        Schema::dropIfExists('orden_compras_cab');
    }
}