<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCobranzaCabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cobranza_cab', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->integer('sucursal_id')->unsigned();
            $table->integer('habilitacion_id')->unsigned();
            $table->integer('moneda_id')->unsigned();
            $table->decimal('valor_cambio', 14, 2)->default(1);
            $table->integer('cliente_id')->unsigned();
            $table->char('estado', 1)->default('R');
            $table->string('comentario')->nullable();
            $table->decimal('monto_total', 12)->default(0);
            $table->decimal('vuelto', 12)->default(0);
            $table->timestamps();

            $table->foreign('sucursal_id')->references('id')->on('sucursales');
            $table->foreign('habilitacion_id')->references('id')->on('habilitacion_caja');
            $table->foreign('moneda_id')->references('id')->on('monedas');
            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cobranza_cab');
    }
}
