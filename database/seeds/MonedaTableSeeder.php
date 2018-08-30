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
        $moneda->codigo = 'GS';
        $moneda->descripcion = 'Guaraní';
        $moneda->simbolo = 'Gs';
        $moneda->save();

        $moneda = new Moneda();
        $moneda->codigo = 'US';
        $moneda->descripcion = 'Dólar';
        $moneda->simbolo = 'USD';
        $moneda->save();

        $moneda = new Moneda();
        $moneda->codigo = 'PA';
        $moneda->descripcion = 'Peso Argentino';
        $moneda->simbolo = 'Ps';
        $moneda->save();

        $moneda = new Moneda();
        $moneda->codigo = 'RE';
        $moneda->descripcion = 'Real';
        $moneda->simbolo = 'R$';
        $moneda->save();
    }
}
