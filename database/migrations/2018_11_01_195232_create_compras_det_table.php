<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras_det', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compra_cab_id')->unsigned();
            $table->integer('articulo_id')->unsigned();
            $table->decimal('cantidad', 14, 2);
            $table->decimal('costo_unitario', 14, 2);
            $table->decimal('costo_promedio', 14, 2);
            $table->decimal('porcentaje_descuento', 14, 2);
            $table->decimal('monto_descuento', 14, 2);
            $table->decimal('porcentaje_iva', 14, 2);
            $table->decimal('monto_exenta', 14, 2);
            $table->decimal('monto_gravada', 14, 2);
            $table->decimal('monto_iva', 14, 2);
            $table->decimal('sub_total', 14, 2);
            $table->timestamps();

            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->foreign('compra_cab_id')->references('id')->on('compras_cab');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras_det');
    }
}
