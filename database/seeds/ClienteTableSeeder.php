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
        $cliente->tipo_persona = 'F';
        $cliente->nombre = 'Alexis Ramón';
        $cliente->apellido = 'Fernandez Cantero';
        $cliente->ruc = '5568434-3';
        $cliente->nro_cedula = 5568434;
        $cliente->telefono_celular = 973582620;
        $cliente->direccion = 'Manuel Ortiz Guerrero 1024';
        $cliente->correo_electronico = 'alexis.fernandez.rc@gmail.com';
        $cliente->zona_id = $zona->id;
        $cliente->tipo_cliente_id = $tipo_cliente->id;
        $cliente->save();

        $cliente = new Cliente();
        $cliente->tipo_persona = 'F';
        $cliente->nombre = 'Nidia Sofía Alejandra';
        $cliente->apellido = 'Fernandez Cerrano';
        $cliente->nro_cedula = 7700590;
        $cliente->save();
    }
}
