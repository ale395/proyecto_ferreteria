<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticulosTable extends Migration
{
    
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('articulos', function (Blueprint $table) {
                $table->increments('id');
                $table->string('codigo', 20)->unique();
                $table->string('descripcion', 100);
                $table->decimal('codigo_barra', 14, 2)->default(0);
                $table->decimal('costo', 14, 2)->default(0);
                $table->decimal('porcentaje ganancia', 14, 2)->default(0);
                $table->string('comentario', 100)->nullable();
                $table->integer('impuesto_id')->unsigned()->nullable();
                $table->integer('grupo_id')->unsigned()->nullable();
                $table->integer('familia_id')->unsigned()->nullable();
                $table->integer('linea_id')->unsigned()->nullable();
                $table->integer('unidad_medida_id')->unsigned()->nullable();
                $table->boolean('control_existencia')->default(true);
                $table->boolean('vendible')->default(true);
                $table->boolean('activo')->default(true);
                $table->timestamps();
    
            });
        }
    
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('articulos');
        }
}
