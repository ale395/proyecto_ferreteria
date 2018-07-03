<?php

use Illuminate\Database\Seeder;
use App\Concepto;
use App\Modulo;

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
        $modulo_cm = Role::where('modulo', 'CR')->first();

        $concepto = new Concepto();
        $concepto->concepto = 'FCO';
        $concepto->nombre_concepto = 'Factura Contado';
        $concepto->modulo_id = $modulo_vt->id;
        $concepto->tipo_concepto = 'D';
        $concepto->muev_stock = 'S';
        $concepto->save();
        $concepto->modulos()->attach($modulo_id);

    }
}
