<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasChequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras_cheques', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compras_cab_id')->unsigned();
            $table->integer('banco_id')->unsigned();
            $table->integer('moneda_id')->unsigned();
            $table->decimal('valor_cambio', 14, 2)->default(1);
            $table->string('nro_cuenta', 20);
            $table->string('librador', 50);
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->decimal('importe', 14, 2)->default(0);
            $table->timestamps();

            $table->foreign('compras_cab_id')->references('id')->on('compras_cab');
            $table->foreign('banco_id')->references('id')->on('bancos');
            $table->foreign('moneda_id')->references('id')->on('monedas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras_cheques');
    }
}
