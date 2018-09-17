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
        $moneda->descripcion = 'Guaraníes';
        $moneda->simbolo = 'Gs';
        $moneda->maneja_decimal = false;
        $moneda->save();

        $moneda = new Moneda();
        $moneda->codigo = 'US';
        $moneda->descripcion = 'Dólar Americano';
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

        $moneda = new Moneda();
        $moneda->codigo = 'EU';
        $moneda->descripcion = 'Euro';
        $moneda->simbolo = 'E';
        $moneda->save();
    }
}
