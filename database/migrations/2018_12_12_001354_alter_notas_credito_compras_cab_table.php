<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotasCreditoComprasCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nota_credito_compras_cab', function (Blueprint $table) {
            $table->integer('compra_cab_id')->unsigned();
            $table->date('fecha_vigencia_timbrado');
            $table->string('nro_nota_credito', 14)->change();

            $table->foreign('compra_cab_id')->references('id')->on('compras_cab');
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
        if(Schema::hasColumn('nota_credito_compras_cab','compra_cab_id') ) {
            Schema::table('nota_credito_compras_cab', function (Blueprint $table) {
                $table->dropColumn('compra_cab_id');
            });
        }

        //Pablo - 14/10/2018 - controlamos si existen las columnas primero antes del drop        
        if(Schema::hasColumn('nota_credito_compras_cab','fecha_vigencia_timbrado') ) {
            Schema::table('nota_credito_compras_cab', function (Blueprint $table) {
                $table->dropColumn('fecha_vigencia_timbrado');
            });
        }

        //Pablo - 14/10/2018 - controlamos si existen las columnas primero antes del drop        
        if(Schema::hasColumn('nota_credito_compras_cab','nro_nota_credito') ) {
            Schema::table('nro_nota_credito', function (Blueprint $table) {
                $table->dropColumn('nro_nota_credito');
            });
        }
    }
}
