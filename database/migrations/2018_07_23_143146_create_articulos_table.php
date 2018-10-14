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
                $table->string('codigo_barra', 14, 2)->default(0);
                $table->decimal('porcentaje_ganancia', 14, 2)->default(0);
                $table->integer('impuesto_id')->unsigned()->nullable();
                $table->integer('rubro_id')->unsigned()->nullable();
                $table->integer('familia_id')->unsigned()->nullable();
                $table->integer('linea_id')->unsigned()->nullable();
                $table->integer('unidad_medida_id')->unsigned()->nullable();
                $table->string('comentario', 100)->nullable();
                $table->decimal('ultimo_costo', 14, 2)->default(0);
                $table->decimal('ultimo_costo_sin_iva', 14, 2)->default(0);
                $table->decimal('costo_promedio', 14, 2)->default(0);
                $table->decimal('costo_promedio_sin_iva', 14, 2)->default(0);
                $table->date('fecha_ultima_compra')->nullable();
                $table->integer('stock_minimo')->unsigned()->nullable();
                $table->string('img_producto')->default('default-img_producto.jpg');
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
