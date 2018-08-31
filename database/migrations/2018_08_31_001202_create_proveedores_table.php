<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 100);
            $table->string('apellido', 100)->nullable();
            $table->string('ruc', 20)->nullable();
            $table->integer('nro_documento')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('correo_electronico', 100)->nullable();
            $table->integer('tipo_vendedor_id')->unsigned();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('tipo_cliente_id')->references('id')->on('tipos_clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
}
