<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterExistenciaArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('existencia_articulos', function (Blueprint $table) {
            //$table->integer('porcentaje_iva');
            $table->date('fecha_ultimo_inventario')->nullable();

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
        if(Schema::hasColumn('existencia_articulos','fecha_ultimo_inventario') ) {
            Schema::table('existencia_articulos', function (Blueprint $table) {
                $table->dropColumn('fecha_ultimo_inventario');
            });
        }
    }
}
