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
            $table->integer('movimiento_id')->unsigned();
            $table->integer('tipo_movimiento')->unsigned();
            $table->date('fecha_movimiento')->nullable();
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
        //Pablo - 14/10/2018 - controlamos si existen las columnas primero antes del drop        
        if(Schema::hasColumn('movimientos_articulos','fecha_movimiento') ) {
            Schema::table('movimientos_articulos', function (Blueprint $table) {
                $table->dropColumn('fecha_movimiento');
            });
        }
    }
}
