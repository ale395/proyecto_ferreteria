<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEmpleadosTiposEmpleados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados_tipos_empleados', function (Blueprint $table) {
            $table->integer('empleado_id');
            $table->integer('tipo_empleado_id');
            $table->timestamps();

            $table->primary('empleado_id', 'tipo_empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('tipo_empleado_id')->references('id')->on('tipos_empleados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleados_tipos_empleados');
    }
}
