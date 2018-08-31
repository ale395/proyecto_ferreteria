<?php

use App\Articulo;
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
        $articulo->save();

        $articulo = new Articulo();
        $articulo->codigo = '0000003';
        $articulo->descripcion = 'Foco XY';
        $articulo->save();

        $articulo = new Articulo();
        $articulo->codigo = '0000004';
        $articulo->descripcion = 'Ducha AABB';
        $articulo->save();

        $articulo = new Articulo();
        $articulo->codigo = '0000006';
        $articulo->descripcion = 'Cortadora de Cesped XY';
        $articulo->save();
    }
}
