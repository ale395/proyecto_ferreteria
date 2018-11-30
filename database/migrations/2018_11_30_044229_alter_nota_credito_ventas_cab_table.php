<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotaCreditoVentasCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nota_credito_ventas_cab', function (Blueprint $table) {
            $table->integer('factura_cab_id')->unsigned();

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
        Schema::table('nota_credito_ventas_cab', function (Blueprint $table) {
            $table->dropColumn('factura_cab_id');
        });
    }
}
