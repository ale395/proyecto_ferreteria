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
            $table->integer('porcentaje_iva');
            $table->decimal('total_exenta', 14, 2);
            $table->decimal('total_gravada', 14, 2);
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
        Schema::table('orden_compras_det', function (Blueprint $table) {
            $table->dropColumn('porcentaje_iva');
            $table->dropColumn('total_exenta');
            $table->dropColumn('total_gravada');
            $table->renameColumn('sub_total', 'monto_total');
        });
    }
}
