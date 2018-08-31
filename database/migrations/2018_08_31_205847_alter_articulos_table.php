<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterArticulosTable extends Migration
{
 
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('articulos', function (Blueprint $table) {
                $table->foreign('impuesto_id')->references('id')->on('impuestos');
                $table->foreign('grupo_id')->references('id')->on('grupos');
                $table->foreign('familia_id')->references('id')->on('familias');
                $table->foreign('linea_id')->references('id')->on('lineas');
                $table->foreign('unidad_medida_id')->references('id')->on('unidad_medidas');
            });
        }
    
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('articulos', function (Blueprint $table) {
                $table->dropForeign('articulos_impuesto_id_foreign');
                $table->dropForeign('articulos_grupo_id_foreign');
                $table->dropForeign('articulos_familia_id_foreign');
                $table->dropForeign('articulos_linea_id_foreign');
                $table->dropForeign('articulos_unidad_medida_id_foreign');
            });
        }
    }
    

