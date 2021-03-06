<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotaCreditoVentasCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nota_credito_ventas_cab', function (Blueprint $table) {
            $table->increments('id');
            $table->char('tipo_nota_credito', 2)->default('DV');//DV = Devolucion, DC = Descuento
            $table->integer('serie_id')->unsigned();
            $table->integer('nro_nota_credito')->unsigned();
            $table->integer('cliente_id')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            //$table->integer('lista_precio_id')->unsigned();
            $table->integer('moneda_id')->unsigned();
            $table->decimal('valor_cambio', 14, 2)->default(1);
            $table->date('fecha_emision');
            $table->decimal('monto_total', 14, 2)->default(0);
            $table->char('estado', 1)->default('P');//P = Pendiente, C = Cobrado, A = Anulada
            $table->string('comentario')->nullable();
            $table->integer('usuario_id')->unsigned();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
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
        Schema::dropIfExists('nota_credito_ventas_cab');
    }
}
