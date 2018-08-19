<?php

use App\Zona;
use App\Cliente;
use App\ClasificacionCliente;
use Illuminate\Database\Seeder;

class ClienteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zona = Zona::where('codigo', '002')->first();
        $tipo_cliente = ClasificacionCliente::where('codigo', '002')->first();

        $cliente = new Cliente();
        $cliente->codigo = '001';
        $cliente->nombre = 'Alexis Ramón';
        $cliente->apellido = 'Fernandez Cantero';
        $cliente->ruc = '5568434-3';
        $cliente->nro_documento = 5568434;
        $cliente->telefono = '0973582620';
        $cliente->direccion = 'Manuel Ortiz Guerrero 1024';
        $cliente->correo_electronico = 'alexis.fernandez.rc@gmail.com';
        $cliente->zona_id = $zona->id;
        $cliente->tipo_cliente_id = $tipo_cliente->id;
        $cliente->save();

        $cliente = new Cliente();
        $cliente->codigo = '002';
        $cliente->nombre = 'Nidia Sofía Alejandra';
        $cliente->apellido = 'Fernandez Cerrano';
        //$cliente->ruc = '';
        $cliente->nro_documento = 7700590;
        //$cliente->telefono = '0973582620';
        //$cliente->direccion = 'Manuel Ortiz Guerrero 1024';
        //$cliente->correo_electronico = 'alexis.fernandez.rc@gmail.com';
        $cliente->zona_id = $zona->id;
        $cliente->tipo_cliente_id = $tipo_cliente->id;
        $cliente->save();
    }
}
