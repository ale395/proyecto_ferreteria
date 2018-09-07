<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nro_cedula')->unsigned()->unique();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('direccion', 100);
            $table->integer('telefono_celular')->unsigned();
            $table->string('correo_electronico', 100);
            $table->date('fecha_nacimiento');
            $table->string('avatar')->default('default-avatar.jpg');
            /*$table->string('nombre_contacto1', 100);
            $table->integer('telefono_contacto1')->unsigned();
            $table->char('relacion_contacto1', 1);
            $table->string('nombre_contacto2', 100)->nullable();
            $table->integer('telefono_contacto2')->unsigned()->nullable();
            $table->char('relacion_contacto2', 1)->nullable();*/
            $table->boolean('activo')->default(true);
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
        Schema::dropIfExists('empleados');
    }
}
