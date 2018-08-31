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
use App\TipoProveedor;
use App\Proveedor;


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
        $unidadmedida = new UnidadMedida();
        $unidadmedida->num_umedida = 'UNI';
        $unidadmedida->descripcion = 'UNIDAD';
        $unidadmedida->save();

       	//Concepto ajuste por default - ajuste de existencia
        $conceptoajuste = new ConceptoAjuste();
        $conceptoajuste->num_concepto = '001';
        $conceptoajuste->descripcion = 'AJUSTE DE EXISTENCIA';
        $conceptoajuste->save();

        //tipo cliente por default - ajuste de existencia
        $tipoclientemayo = new ClasificacionCliente();
        $tipoclientemayo->codigo = '001';
        $tipoclientemayo->nombre = 'Mayorista';
        $tipoclientemayo->save();

        //tipo cliente por default - ajuste de existencia
        $tipoclientemino = new ClasificacionCliente();
        $tipoclientemino->codigo = '002';
        $tipoclientemino->nombre = 'Minorista';
        $tipoclientemino->save();

        //tipo proveedor 
        $tipoproveedor = new TipoProveedor();
        $tipoproveedor->codigo = '001';
        $tipoproveedor->nombre = 'Proveedor Local';
        $tipoproveedor->save();

        $proveedor = new Proveedor();
        $proveedor->codigo = 'xxxxxxx-x';
        $proveedor->nombre = 'Proveedor Generico';
        $proveedor->razon_social = 'Proveedor Generico';
        $proveedor->save();

        /*
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
        */
    }
}
