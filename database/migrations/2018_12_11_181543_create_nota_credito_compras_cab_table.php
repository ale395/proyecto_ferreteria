<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotaCreditoComprasCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nota_credito_compras_cab', function (Blueprint $table) {
            $table->increments('id');
            $table->char('tipo_nota_credito', 2)->default('DV');//DV = Devolucion, DC = Descuento
            $table->integer('serie_id')->unsigned();
            $table->integer('nro_nota_credito')->unsigned();
            $table->integer('proveedor_id')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            //$table->integer('lista_precio_id')->unsigned();
            $table->integer('moneda_id')->unsigned();
            $table->decimal('valor_cambio', 14, 2)->default(1);
            $table->date('fecha_emision');
            $table->decimal('monto_total', 14, 2)->default(0);
            $table->decimal('total_exenta', 14, 2)->default(0);
            $table->decimal('total_gravada', 14, 2)->default(0);
            $table->decimal('total_iva', 14, 2)->default(0);
            $table->char('estado', 1)->default('A');//A = Aplicada
            $table->string('comentario')->nullable();
            $table->integer('usuario_id')->unsigned();
            $table->timestamps();

            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
            $table->foreign('moneda_id')->references('id')->on('monedas');
            $table->foreign('serie_id')->references('id')->on('series');
            $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nota_credito_compras_cab');
    }
}
