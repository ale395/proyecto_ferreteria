<?php

use App\Empleado;
use App\Sucursal;
use APP\TipoEmpleado;
use Illuminate\Database\Seeder;

class EmpleadoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendedor = TipoEmpleado::where('codigo', 'VEND')->first();
        $cajero = TipoEmpleado::where('codigo', 'CAJE')->first();
        $sucursal_central = Sucursal::where('codigo', 'S001')->first();
        $sucursal_sanber = Sucursal::where('codigo', 'S003')->first();

        $empleado = new Empleado();
        $empleado->setNombre('Alexis Ramón');
        $empleado->setApellido('Fernández Cantero');
        $empleado->setDireccion('Manuel Ortiz Guerrero - San Lorenzo');
        $empleado->setTelefonoCelular(973582620);
        $empleado->setNroCedula(5568434);
        $empleado->setFechaNacimiento('28/03/1995');
        $empleado->setCorreoElectronico('alexis.fernandez.rc@gmail.com');
        $empleado->avatar = '5568434.jpg';
        $empleado->setSucursalActual($sucursal_central->getId());
        $empleado->save();
        $empleado->tiposEmpleados()->sync([$vendedor->id, $cajero->id]);
        $empleado->sucursales()->sync([$sucursal_central->id, $sucursal_sanber->id]);

        $empleado = new Empleado();
        $empleado->setNombre('Usuario');
        $empleado->setApellido('Del Sistema');
        $empleado->setDireccion('Sin Definir');
        $empleado->setTelefonoCelular(981882111);
        $empleado->setNroCedula(8888888);
        $empleado->setFechaNacimiento('01/01/1981');
        $empleado->setCorreoElectronico('usuario@ferregest.com');
        $empleado->setSucursalActual($sucursal_central->getId());
        $empleado->save();
        $empleado->tiposEmpleados()->sync($vendedor->id);
        $empleado->sucursales()->sync([$sucursal_central->id]);

        $empleado = new Empleado();
        $empleado->setNombre('Administrador');
        $empleado->setApellido('Del Sistema');
        $empleado->setDireccion('Sin Definir');
        $empleado->setTelefonoCelular(981882111);
        $empleado->setNroCedula(1111111);
        $empleado->setFechaNacimiento('01/01/1981');
        $empleado->setCorreoElectronico('admin@ferregest.com');
        $empleado->setSucursalActual($sucursal_central->getId());
        $empleado->save();
        $empleado->tiposEmpleados()->sync([$vendedor->id, $cajero->id]);
        $empleado->sucursales()->sync([$sucursal_central->id]);

        $empleado = new Empleado();
        $empleado->setNombre('Yanina');
        $empleado->setApellido('Sosa');
        $empleado->setDireccion('Sin Definir');
        $empleado->setTelefonoCelular(981112111);
        $empleado->setNroCedula(123456);
        $empleado->setFechaNacimiento('01/01/1994');
        $empleado->setCorreoElectronico('yani_rsc@hotmail.com');
        $empleado->setSucursalActual($sucursal_central->getId());
        $empleado->save();
        $empleado->tiposEmpleados()->sync([$vendedor->id, $cajero->id]);
        $empleado->sucursales()->sync([$sucursal_central->id]);
    }
}
