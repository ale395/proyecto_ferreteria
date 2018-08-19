<?php

use Illuminate\Database\Seeder;
use App\Familia;
use App\Linea;
use App\Rubro;
use App\UnidadMedida;
use App\ConceptoAjuste;
use App\ClasificacionCliente;
use App\Cajero;
use App\User;


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

        //Concepto ajuste por default - ajuste de existencia
        $familia = new ClasificacionCliente();
        $familia->codigo = '001';
        $familia->nombre = 'Mayorista';
        $familia->save();

        //Concepto ajuste por default - ajuste de existencia
        $familia = new ClasificacionCliente();
        $familia->codigo = '002';
        $familia->nombre = 'Minorista';
        $familia->save();

        //traemos el usuario
        $usuario = User::where('email', 'admin@ferregest.com')->first();
        //creamos el cajero
        $cajero = new Cajero();
        $cajero->num_cajero = '001';
        $cajero->descripcion = 'Administrador';
        $cajero->save();
        //asignamos el usuario al cajeros
        $cajero->usuario()->associate($usuario);
        $cajero->save();

    }
}
