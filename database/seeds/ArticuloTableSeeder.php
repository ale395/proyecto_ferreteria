<?php

use App\Articulo;
use App\Familia;
use App\Linea;
use App\Rubro;
use Illuminate\Database\Seeder;

class ArticuloTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articulo = new Articulo();
        $articulo->codigo = '0000001';
        $articulo->descripcion = 'Pintura Amanecer 1 Litro';
        $articulo->familia_id = Familia::where('num_familia', '005')->first()->id;
        $articulo->linea_id = Linea::where('num_linea', '008')->first()->id;
        $articulo->rubro_id = Rubro::where('num_rubro', '003')->first()->id;
        $articulo->ultimo_costo = 40200.00;
        $articulo->img_producto = 'prod-1.jpg';
        $articulo->costo_promedio = 37152.87;
        $articulo->save();

        $articulo = new Articulo();
        $articulo->codigo = '0000003';
        $articulo->descripcion = 'Foco XY';
        $articulo->familia_id = Familia::where('num_familia', '001')->first()->id;
        $articulo->linea_id = Linea::where('num_linea', '004')->first()->id;
        $articulo->rubro_id = Rubro::where('num_rubro', '001')->first()->id;
        $articulo->ultimo_costo = 3250.00;
        $articulo->costo_promedio = 3741.02;
        $articulo->save();

        $articulo = new Articulo();
        $articulo->codigo = '0000004';
        $articulo->descripcion = 'Ducha AABB';
        $articulo->familia_id = Familia::where('num_familia', '002')->first()->id;
        $articulo->linea_id = Linea::where('num_linea', '004')->first()->id;
        $articulo->rubro_id = Rubro::where('num_rubro', '006')->first()->id;
        $articulo->ultimo_costo = 27800.00;
        $articulo->costo_promedio = 23511.14;
        $articulo->save();

        $articulo = new Articulo();
        $articulo->codigo = '0000006';
        $articulo->descripcion = 'Cortadora de Cesped XY';
        $articulo->familia_id = Familia::where('num_familia', '005')->first()->id;
        $articulo->linea_id = Linea::where('num_linea', '005')->first()->id;
        $articulo->rubro_id = Rubro::where('num_rubro', '001')->first()->id;
        $articulo->ultimo_costo = 157000.00;
        $articulo->costo_promedio = 155012.94;
        $articulo->save();
    }
}
