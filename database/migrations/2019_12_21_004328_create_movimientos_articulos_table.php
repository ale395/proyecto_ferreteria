<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\MovimientoArticulo;

class CreateMovimientosArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos_articulos', function (Blueprint $table) {
            $table->increments('id');
            $table->char('tipo_movimiento', 1); ///F FACTURA Z CREDITO  C COMPRA D DEBITO A AJUSTE I INVENTARIO 
            $table->integer('movimiento_id')->unsigned();
            $table->date('fecha_movimiento');
            $table->integer('articulo_id')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            $table->decimal('cantidad', 14, 2)->unsigned();

            $table->timestamps();

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
        Schema::dropIfExists('movimientos_articulos');
    }
}
