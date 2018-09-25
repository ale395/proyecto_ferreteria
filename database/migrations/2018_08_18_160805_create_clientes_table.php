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
            //$table->string('codigo', 20)->unique();
            $table->char('tipo_persona', 1);
            $table->string('nombre', 100)->nullable();
            $table->string('apellido', 100)->nullable();
            $table->string('razon_social', 100)->nullable();
            $table->string('ruc', 20)->nullable()->unique();
            $table->integer('nro_cedula')->nullable()->unique();
            $table->string('telefono_celular', 20)->nullable();
            $table->string('telefono_linea_baja', 20)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('correo_electronico', 100)->nullable();
            $table->integer('zona_id')->unsigned()->nullable();
            $table->integer('tipo_cliente_id')->unsigned()->nullable();
            $table->decimal('monto_saldo', 14, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('zona_id')->references('id')->on('zonas');
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
        Schema::dropIfExists('clientes');
    }
}
