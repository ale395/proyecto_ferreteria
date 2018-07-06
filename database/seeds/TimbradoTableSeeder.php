<?php

use App\Timbrado;
use Illuminate\Database\Seeder;

class TimbradoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timbrado1 = new Timbrado();
        $timbrado1->nro_timbrado = "123456";
        $timbrado1->fecha_vigencia = "01/01/2019";
        $timbrado1->estado = "A";
        $timbrado1->save();

        $timbrado2 = new Timbrado();
        $timbrado2->nro_timbrado = "999999";
        $timbrado2->fecha_vigencia = "01/06/2018";
        $timbrado2->estado = "I";
        $timbrado2->save();
    }
}
