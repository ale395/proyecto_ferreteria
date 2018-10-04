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
            $table->integer('articulo_id')->nullable();
            $table->integer('deposito_id')->nullable();
            $table->decimal('cantidad', 25, 4)->nullable();
            $table->date('fecha_ultimo_inventario')->nullable();
            $table->decimal('costo_ultimo_inventario', 25, 4)->nullable();

            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->foreign('deposito_id')->references('id')->on('depositos');                       
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
