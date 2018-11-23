<?php

use App\Moneda;
use App\Cotizacion;
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
        
        //Pablo - Cotizacion 1 para Guaraníes por Defecto... y puede que ponga para las demás monedas también por default
        $cotizacion_gs = new Cotizacion();
        $moneda_gs = Moneda::where('codigo', 'GS')->first();
        $cotizacion_gs->fecha_cotizacion = "01/01/2018";
        $cotizacion_gs->moneda_id = $moneda_gs->id;
        $cotizacion_gs->valor_compra = 1;
        $cotizacion_gs->valor_venta = 1;
        $cotizacion_gs->save();
    }
}
