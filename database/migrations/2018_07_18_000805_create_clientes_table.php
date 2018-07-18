<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombres', 100);
            $table->string('apellidos', 100)->nullable();
            $table->integer('nro_cedula')->nullable();
            $table->string('ruc', 20)->nullable();
            $table->string('nro_telefono', 20)->nullable();
            $table->string('correo_electronico', 30)->nullable();
            $table->integer('pais_id')->unsigned()->nullable();
            $table->integer('ciudad_id')->unsigned()->nullable();
            $table->string('direccion', 50)->nullable();
            $table->integer('categoria_id')->unsigned();
            $table->integer('lista_precio_id')->unsigned()->nullable();
            $table->integer('vendedor_id')->unsigned()->nullable();
            $table->char('estado', 1)->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
