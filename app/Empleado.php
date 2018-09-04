<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $fillable = [
        'nro_cedula', 'nombre', 'apellido', 'direccion', 'zona_id', 'telefono_celular', 'telefono_linea_baja', 'correo_electronico', 'fecha_nacimiento', 'activo',
    ];

    public function zona()
    {
        return $this->belongsTo('App\Zona');
    }
    
}
