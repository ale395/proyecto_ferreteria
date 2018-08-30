<?php

use App\Moneda;
use Illuminate\Database\Seeder;

class MonedaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $moneda = new Moneda();
        $moneda->moneda = 'GS';
        $moneda->codigo = 'GS';
        $moneda->descripcion = 'Guaraní';
        $moneda->simbolo = 'Gs';
        $moneda->save();

        $moneda = new Moneda();
        $moneda->moneda = 'US';
        $moneda->codigo = 'US';
        $moneda->descripcion = 'Dólar';
        $moneda->simbolo = 'USD';
        $moneda->save();

        $moneda = new Moneda();
        $moneda->moneda = 'PA';
        $moneda->codigo = 'PA';
        $moneda->descripcion = 'Peso Argentino';
        $moneda->simbolo = 'Ps';
        $moneda->save();

        $moneda = new Moneda();
        $moneda->moneda = 'RE';
        $moneda->codigo = 'RE';
        $moneda->descripcion = 'Real';
        $moneda->simbolo = 'R$';
        $moneda->save();
    }
}
