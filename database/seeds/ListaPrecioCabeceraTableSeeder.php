<?php

use App\Moneda;
use App\ListaPrecioCabecera;
use Illuminate\Database\Seeder;

class ListaPrecioCabeceraTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lista = new ListaPrecioCabecera();
        $lista->codigo = '001';
        $lista->nombre = 'Minorista GS';
        $lista->moneda_id = Moneda::where('codigo', 'GS')->first()->id;
        $lista->save();

        $lista = new ListaPrecioCabecera();
        $lista->codigo = '002';
        $lista->nombre = 'Mayorista GS';
        $lista->moneda_id = Moneda::where('codigo', 'GS')->first()->id;
        $lista->save();
    }
}
