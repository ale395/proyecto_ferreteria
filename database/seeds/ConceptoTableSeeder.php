<?php

use Illuminate\Database\Seeder;
use App\Modulo;
use App\Role;
use App\Concepto;

class ConceptoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modulo_vt = Modulo::where('modulo', 'VT')->first();
        $modulo_cm = Modulo::where('modulo', 'CR')->first();

        $concepto = new Concepto();
        $concepto->concepto = 'FCO';
        $concepto->nombre_concepto = 'Factura Contado';
        $concepto->modulo_id = $modulo_vt->id;
        $concepto->tipo_concepto = 'D';
        $concepto->muev_stock = 'R';
        $concepto->save();

        $concepto = new Concepto();
        $concepto->concepto = 'FCR';
        $concepto->nombre_concepto = 'Factura Crédito';
        $concepto->modulo_id = $modulo_vt->id;
        $concepto->tipo_concepto = 'D';
        $concepto->muev_stock = 'R';
        $concepto->save();

        $concepto = new Concepto();
        $concepto->concepto = 'NCR';
        $concepto->nombre_concepto = 'Nota de Crédito';
        $concepto->modulo_id = $modulo_vt->id;
        $concepto->tipo_concepto = 'C';
        $concepto->muev_stock = 'S';
        $concepto->save();

        $concepto = new Concepto();
        $concepto->concepto = 'NDB';
        $concepto->nombre_concepto = 'Nota de Débito';
        $concepto->modulo_id = $modulo_vt->id;
        $concepto->tipo_concepto = 'D';
        $concepto->muev_stock = 'N';
        $concepto->save();

    }
}
