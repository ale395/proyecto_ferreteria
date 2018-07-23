<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListaPreciosDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lista_precios_detalle', function (Blueprint $table) {
            $table->integer('lista_precio_id')->unsigned();
            $table->integer('articulo_id')->unsigned();
            $table->date('fecha_vigencia');
            $table->decimal('precio', 12, 2);
            $table->timestamps();

            $table->primary(['lista_precio_id', 'articulo_id', 'fecha_vigencia']);
            $table->foreign('lista_precio_id')->references('id')->on('lista_precios_cabecera');
            //$table->foreign('articulo_id')->references('id')->on('articulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lista_precios_detalle');
    }
}
