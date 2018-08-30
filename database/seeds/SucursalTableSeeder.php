<?php

use App\Sucursal;
use Illuminate\Database\Seeder;

class SucursalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sucursal = new Sucursal();
        $sucursal->codigo = 'S001';
        $sucursal->nombre = 'Casa Central';
        $sucursal->direccion = '25 de Mayo casi Antequera - Asunción';
        $sucursal->codigo_punto_expedicion = '001';
        $sucursal->save();

        $sucursal = new Sucursal();
        $sucursal->codigo = 'S002';
        $sucursal->nombre = 'Sucursal Fdo de la Mora';
        $sucursal->direccion = 'Eusebio Ayala casi Calle Ultima - Fernando de la Mora';
        $sucursal->codigo_punto_expedicion = '003';
        $sucursal->activo = false;
        $sucursal->save();

        $sucursal = new Sucursal();
        $sucursal->codigo = 'S003';
        $sucursal->nombre = 'Sucursal SanBer';
        $sucursal->direccion = 'Calle Mcal Lopez 1024 - San Bernardino';
        $sucursal->codigo_punto_expedicion = '004';
        $sucursal->save();

        $sucursal = new Sucursal();
        $sucursal->codigo = 'S004';
        $sucursal->nombre = 'Sucursal San Lorenzo';
        $sucursal->direccion = '10 de Agosto casi Saturio Ríos - San Lorenzo';
        $sucursal->codigo_punto_expedicion = '002';
        $sucursal->save();
    }
}
