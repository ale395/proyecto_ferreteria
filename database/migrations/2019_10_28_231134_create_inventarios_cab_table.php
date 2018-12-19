<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventariosCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios_cab', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nro_inventario')->unique()->unsigned();
           // $table->integer('empleado_id')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            $table->date('fecha_emision');
            $table->string('motivo')->nullable();
            $table->integer('usuario_id')->unsigned();
            $table->decimal('monto_total', 14, 2)->default(0);
            $table->timestamps();

            //$table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
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
        Schema::dropIfExists('inventarios_cab');
    }
}
