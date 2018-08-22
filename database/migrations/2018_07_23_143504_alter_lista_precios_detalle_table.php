<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterListaPreciosDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lista_precios_detalle', function (Blueprint $table) {
            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->foreign('lista_precio_id')->references('id')->on('lista_precios_cabecera');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lista_precios_detalle', function (Blueprint $table) {
            $table->dropForeign('lista_precios_detalle_articulo_id_foreign');
            $table->dropForeign('lista_precios_detalle_lista_precio_id_foreign');
        });
    }
}
