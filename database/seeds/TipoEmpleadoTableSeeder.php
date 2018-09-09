<?php

use App\TipoEmpleado;
use Illuminate\Database\Seeder;

class TipoEmpleadoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipo_empleado = new TipoEmpleado();
        $tipo_empleado->codigo = 'VEND';
        $tipo_empleado->nombre = 'Vendedor';
        $tipo_empleado->save();

        $tipo_empleado = new TipoEmpleado();
        $tipo_empleado->codigo = 'CAJE';
        $tipo_empleado->nombre = 'Cajero';
        $tipo_empleado->save();

        $tipo_empleado = new TipoEmpleado();
        $tipo_empleado->codigo = 'SEGU';
        $tipo_empleado->nombre = 'Seguridad';
        $tipo_empleado->save();

        $tipo_empleado = new TipoEmpleado();
        $tipo_empleado->codigo = 'ADMI';
        $tipo_empleado->nombre = 'Administración';
        $tipo_empleado->save();
    }
}
