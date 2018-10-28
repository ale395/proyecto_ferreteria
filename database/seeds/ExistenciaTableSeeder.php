<?php

use App\Articulo;
use App\Sucursal;
use App\ExistenciaArticulo;
use Illuminate\Database\Seeder;

class ExistenciaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sucursal_central = Sucursal::where('codigo', 'S001')->first();
        $articulo1 = Articulo::where('codigo', '0000001')->first();
        $articulo2 = Articulo::where('codigo', '0000004')->first();
        $articulo3 = Articulo::where('codigo', '0000006')->first();
        $articulo4 = Articulo::where('codigo', '0000003')->first();

        $existencia = new ExistenciaArticulo();
        $existencia->setArticuloId($articulo1->id);
        $existencia->setSucursalId($sucursal_central->id);
        $existencia->setCantidad(10);
        $existencia->save();

        $existencia = new ExistenciaArticulo();
        $existencia->setArticuloId($articulo2->id);
        $existencia->setSucursalId($sucursal_central->id);
        $existencia->setCantidad(5);
        $existencia->save();

        $existencia = new ExistenciaArticulo();
        $existencia->setArticuloId($articulo3->id);
        $existencia->setSucursalId($sucursal_central->id);
        $existencia->setCantidad(3);
        $existencia->save();

        $existencia = new ExistenciaArticulo();
        $existencia->setArticuloId($articulo4->id);
        $existencia->setSucursalId($sucursal_central->id);
        $existencia->setCantidad(100);
        $existencia->save();
    }
}
