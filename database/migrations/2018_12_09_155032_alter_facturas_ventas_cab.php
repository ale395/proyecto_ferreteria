<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFacturasVentasCab extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facturas_ventas_cab', function (Blueprint $table) {
            $table->char('serie', 7)->nullable();//001-002
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facturas_ventas_cab', function (Blueprint $table) {
            $table->dropColumn('serie');
        });
    }
}
