<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosComprasAfectadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_compras_afectadas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pagos_id');
            $table->integer('compras_id');
            $table->decimal('importe_afectado', 14, 2);
            $table->timestamps();

            $table->foreign('compras_id')->references('id')->on('compras_cab');
            $table->foreign('pagos_id')->references('id')->on('pagos');        

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos_compras_afectadas');
    }
}
