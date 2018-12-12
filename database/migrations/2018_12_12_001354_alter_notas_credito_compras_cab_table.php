<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotasCreditoComprasCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nota_credito_compras_cab', function (Blueprint $table) {
            $table->integer('compra_cab_id')->unsigned();

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
        Schema::table('nota_credito_compras_cab', function (Blueprint $table) {
            $table->dropColumn('compra_cab_id');
        });
    }
}
