<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ExistenciaArticulo;

class CreateExistenciaArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('existencia_articulos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('articulo_id')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            $table->decimal('cantidad', 14, 2)->unsigned();
            $table->timestamps();
            //$table->date('fecha_ultimo_inventario')->nullable();
            //$table->decimal('costo_ultimo_inventario', 14, 2)->nullable();

            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');                       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('existencia_articulos');
    }
}
