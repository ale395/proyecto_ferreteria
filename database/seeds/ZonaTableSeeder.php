<?php

use App\Zona;
use Illuminate\Database\Seeder;

class ZonaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zona = new Zona();
        $zona->codigo = '001';
        $zona->nombre = 'Asunción';
        $zona->save();

        $zona = new Zona();
        $zona->codigo = '002';
        $zona->nombre = 'San Lorenzo';
        $zona->save();

        $zona = new Zona();
        $zona->codigo = '003';
        $zona->nombre = 'San Bernardino';
        $zona->save();

        $zona = new Zona();
        $zona->codigo = '004';
        $zona->nombre = 'Caacupe';
        $zona->save();

        $zona = new Zona();
        $zona->codigo = '005';
        $zona->nombre = 'Fernando de la Mora';
        $zona->save();

        $zona = new Zona();
        $zona->codigo = '006';
        $zona->nombre = 'Itaugua';
        $zona->save();

        $zona = new Zona();
        $zona->codigo = '007';
        $zona->nombre = 'Ita';
        $zona->save();

        $zona = new Zona();
        $zona->codigo = '008';
        $zona->nombre = 'Paraguari';
        $zona->save();

        $zona = new Zona();
        $zona->codigo = '009';
        $zona->nombre = 'Guarambare';
        $zona->save();

        $zona = new Zona();
        $zona->codigo = '010';
        $zona->nombre = 'Villa Morra';
        $zona->save();
    }
}
