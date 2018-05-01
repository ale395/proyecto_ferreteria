<?php

use Illuminate\Database\Seeder;
use App\Modulo;

class ModuloTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modulo = new Modulo();
        $modulo->modulo = 'VT';
        $modulo->descripcion = 'Ventas';
        $modulo->save();

        $modulo = new Modulo();
        $modulo->modulo = 'CR';
        $modulo->descripcion = 'Compras';
        $modulo->save();

        $modulo = new Modulo();
        $modulo->modulo = 'ST';
        $modulo->descripcion = 'Stock';
        $modulo->save();

        $modulo = new Modulo();
        $modulo->modulo = 'CC';
        $modulo->descripcion = 'Cuentas por Cobrar';
        $modulo->save();

        $modulo = new Modulo();
        $modulo->modulo = 'CP';
        $modulo->descripcion = 'Cuentas por Pagar';
        $modulo->save();

        $modulo = new Modulo();
        $modulo->modulo = 'TS';
        $modulo->descripcion = 'Tesorería';
        $modulo->save();

        $modulo = new Modulo();
        $modulo->modulo = 'CO';
        $modulo->descripcion = 'Contabilidad';
        $modulo->save();

        $modulo = new Modulo();
        $modulo->modulo = 'PR';
        $modulo->descripcion = 'Producción';
        $modulo->save();
    }
}
