<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAjustesInventariosCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ajustes_inventarios_cab', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('nro_pedido')->unique()->unsigned();
            $table->integer('empleado_id')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            $table->integer('concepto_ajuste_id')->unsigned();
            $table->date('fecha_emision');
            $table->string('motivos');

            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
            $table->foreign('concepto_ajuste_id')->references('id')->on('conceptos_ajustes');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ajustes_inventarios_cab');
    }
}
