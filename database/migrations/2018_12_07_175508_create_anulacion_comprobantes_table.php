<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnulacionComprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anulacion_comprobantes', function (Blueprint $table) {
            $table->increments('id');
            $table->char('tipo_comprobante', 1);//F = Factura, N = Nota Credito
            $table->integer('comprobante_id')->unsigned();
            $table->integer('motivo_anulacion_id')->unsigned();
            $table->date('fecha_anulacion');
            $table->timestamps();

            $table->foreign('motivo_anulacion_id')->references('id')->on('motivos_anulaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anulacion_comprobantes');
    }
}
