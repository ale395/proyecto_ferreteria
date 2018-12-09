<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNotasCreditoCab extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nota_credito_ventas_cab', function (Blueprint $table) {
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
        Schema::table('nota_credito_ventas_cab', function (Blueprint $table) {
            $table->dropColumn('serie');
        });
    }
}
