<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterComprasCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compras_cab', function (Blueprint $table) {
            //$table->integer('porcentaje_iva');
            $table->string('nro_factura', 14)->change();
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
        if(Schema::hasColumn('compras_cab','nro_factura') ) {
            Schema::table('compras_cab', function (Blueprint $table) {
                $table->dropColumn('nro_factura');
            });
        }

    }
}
