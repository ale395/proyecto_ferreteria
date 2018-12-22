<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMovimientosArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Pablo - 14/10/2018 - controlamos si existen las columnas primero antes del drop        
        if(Schema::hasColumn('movimientos_articulos','fecha_ultimo_movimiento') ) {
            Schema::table('movimientos_articulos', function (Blueprint $table) {
                $table->dropColumn('fecha_ultimo_movimiento');
            });
        }
    }
}
