<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdenComprasDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_compras_det', function (Blueprint $table) {
            //$table->integer('porcentaje_iva');
            $table->decimal('porcentaje', 8, 4);
            $table->decimal('total_exenta', 14, 2);
            $table->decimal('total_gravada', 14, 2);
            $table->decimal('total_iva', 14, 2);
            $table->renameColumn('monto_total', 'sub_total');
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
        if(Schema::hasColumn('orden_compras_det','porcentaje_iva') ) {
            Schema::table('orden_compras_det', function (Blueprint $table) {
                $table->dropColumn('porcentaje_iva');
            });
        }

        if(Schema::hasColumn('orden_compras_det','total_exenta') ) {
            Schema::table('orden_compras_det', function (Blueprint $table) {
                $table->dropColumn('total_exenta');
            });
        }

        if(Schema::hasColumn('orden_compras_det','total_gravada') ) {
            Schema::table('orden_compras_det', function (Blueprint $table) {
                $table->dropColumn('total_gravada');
            });
        }

        if(Schema::hasColumn('orden_compras_det','total_iva') ) {
            Schema::table('orden_compras_det', function (Blueprint $table) {
                $table->dropColumn('total_iva');
            });
        }

        if(Schema::hasColumn('orden_compras_det','sub_total') ) {
            Schema::table('orden_compras_det', function (Blueprint $table) {
                $table->renameColumn('sub_total', 'monto_total');
            });
        }
    }
}
