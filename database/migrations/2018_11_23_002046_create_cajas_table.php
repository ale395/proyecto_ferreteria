<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->increments('id');
            /*$table->string('codigo', 20)->unique();
            $table->integer('usuario_id')->unsigned();*/
            $table->string('nombre', 50);
            $table->integer('sucursal_id')->unsigned();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            //$table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cajas');
    }
}
