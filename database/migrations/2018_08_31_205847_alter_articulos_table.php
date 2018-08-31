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
                $table->dropForeign('articulos_impuesto_id_foreign"');
            });
        }
    }
    

