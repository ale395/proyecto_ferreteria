<?php

use Illuminate\Database\Seeder;
use App\Familia;
use App\Linea;
use App\Rubro;
use App\UnidadMedida;
use App\ConceptoAjuste;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Aca podemos cargar otros datos que podemos ir cargando por default nomas ya
     * como para la primera instalacion	
     * @return void
     */
    public function run()
    {
    	//Familia de producto por default - Generico
        $familia = new Familia();
        $familia->num_familia = '001';
        $familia->descripcion = 'GENERICO';
        $familia->save();

        //Linea de producto por default - Generico
        $linea = new Linea();
        $linea->num_linea = '001';
        $linea->descripcion = 'GENERICO';
        $linea->save();

    	//Rubro de producto por default - Generico
        $rubro = new Rubro();
        $rubro->num_rubro = '001';
        $rubro->descripcion = 'GENERICO';
        $rubro->save();

    	//Unidad de medida por default - Unidad
        $familia = new UnidadMedida();
        $familia->num_umedida = 'UNI';
        $familia->descripcion = 'UNIDAD';
        $familia->save();

       	//Concepto ajuste por default - ajuste de existencia
        $familia = new ConceptoAjuste();
        $familia->num_concepto = '001';
        $familia->descripcion = 'AJUSTE DE EXISTENCIA';
        $familia->save();

    }
}
