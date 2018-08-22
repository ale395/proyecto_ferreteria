<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->increments('id');
            $table->char('tipo_comprobante', 1);//(F) Factura - (N) Nota de Credito
            $table->char('serie', 6);
            $table->integer('timbrado_id')->unsigned();
            $table->integer('nro_inicial')->unsigned();
            $table->integer('nro_final')->unsigned();
            $table->integer('nro_actual')->unsigned()->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('timbrado_id')->references('id')->on('timbrados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series');
    }
}
