<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->decimal('limite_credito', 14, 2)->default(0);
            $table->integer('lista_precio_id')->nullable();

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
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('limite_credito');
            $table->dropColumn('lista_precio_id');
        });
    }
}
