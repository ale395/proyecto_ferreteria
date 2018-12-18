<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras_cab', function (Blueprint $table) {
            $table->increments('id');
            $table->char('tipo_factura', 2)->default('CO');//CO = Contado, CR = Credito
            $table->integer('nro_factura')->unsigned();
            $table->integer('proveedor_id')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            $table->integer('moneda_id')->unsigned();
            $table->decimal('valor_cambio', 14, 2)->default(1);
            $table->date('fecha_emision');
            $table->decimal('monto_total', 14, 2)->default(0);
            $table->decimal('total_exenta', 14, 2)->default(0);
            $table->decimal('total_gravada', 14, 2)->default(0);
            $table->decimal('total_iva', 14, 2)->default(0);
            $table->decimal('total_descuento', 14, 2)->default(0);
            $table->char('estado', 1)->default('P');//P = Pendiente, C = Cobrado, A = Anulada
            $table->string('comentario')->nullable();
            $table->string('timbrado')->nullable();
            $table->integer('usuario_id')->unsigned();
            $table->timestamps();

            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
            $table->foreign('moneda_id')->references('id')->on('monedas');
            $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras_cab');
    }
}
