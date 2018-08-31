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

                $table->integer('impuesto_id')->unsigned();
                $table->integer('grupo_id')->unsigned();
                $table->integer('familia_id')->unsigned();
                $table->integer('linea_id')->unsigned();
                $table->integer('unidad_medida_id')->unsigned();

                $table->boolean('control_existencia')->default(true);
                $table->boolean('vendible')->default(true);
                $table->boolean('activo')->default(true);
                $table->timestamps();
    
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
            Schema::dropIfExists('articulos');
        }
}
