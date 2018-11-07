<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentaProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuenta_proveedores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compra_id')->unsigned();
            $table->integer('devolucion_compra_id')->unsigned();
            $table->decimal('monto_comprobante', 14, 2);//Monto original del documento
            $table->decimal('monto_saldo', 14, 2);//Monto pendiente de pago
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
        Schema::dropIfExists('cuenta_proveedores');
    }
}
