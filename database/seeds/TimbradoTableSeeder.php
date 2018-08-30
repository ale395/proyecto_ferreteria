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
        $timbrado = new Timbrado();
        $timbrado->nro_timbrado = 10087700;
        $timbrado->fecha_inicio_vigencia = date("Y-m-d",strtotime(str_replace('/', '-', '01-01-2017')));
        $timbrado->fecha_fin_vigencia = date("Y-m-d",strtotime(str_replace('/', '-', '31-12-2017')));
        $timbrado->save();

        $timbrado = new Timbrado();
        $timbrado->nro_timbrado = 15487930;
        $timbrado->fecha_inicio_vigencia = date("Y-m-d",strtotime(str_replace('/', '-', '01-01-2018')));
        $timbrado->fecha_fin_vigencia = date("Y-m-d",strtotime(str_replace('/', '-', '31-12-2018')));
        $timbrado->save();

        $timbrado = new Timbrado();
        $timbrado->nro_timbrado = 16787010;
        $timbrado->fecha_inicio_vigencia = date("Y-m-d",strtotime(str_replace('/', '-', '01-01-2018')));
        $timbrado->fecha_fin_vigencia = date("Y-m-d",strtotime(str_replace('/', '-', '31-12-2018')));
        $timbrado->save();
    }
}
