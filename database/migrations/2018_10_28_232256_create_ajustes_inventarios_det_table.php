<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAjustesInventariosDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ajustes_inventarios_det', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ajuste_inventario_cab_id')->unsigned();
            $table->integer('articulo_id')->unsigned();
            $table->integer('existencia_id')->nullable();
            $table->decimal('cantidad', 14, 2);
            $table->integer('diferencia_inventario')->nullable();
            $table->decimal('costo_unitario', 14, 2);
            $table->decimal('sub_total', 14, 2);
            $table->timestamps();

            $table->foreign('ajuste_inventario_cab_id')->references('id')->on('ajustes_inventarios_cab');
            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->foreign('existencia_id')->references('id')->on('existencia_articulos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ajustes_inventarios_det');
    }
}
