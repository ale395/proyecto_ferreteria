<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->string('razon_social', 100);
            $table->string('ruc', 20);
            $table->string('direccion', 100);
            $table->string('correo_electronico', 100);
            $table->string('sitio_web', 100)->nullable();
            $table->string('eslogan', 100)->nullable();
            $table->string('telefono', 20);
            $table->string('rubro', 100);
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
        Schema::dropIfExists('empresa');
    }
}
