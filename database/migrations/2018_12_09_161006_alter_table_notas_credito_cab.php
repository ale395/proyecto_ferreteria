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
            $table->char('nume_serie', 7)->nullable();//001-002
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
        if(Schema::hasColumn('nota_credito_ventas_cab','nume_serie') ) {
            Schema::table('nota_credito_ventas_cab', function (Blueprint $table) {
                $table->dropColumn('nume_serie');
            });
        }

    }
}
