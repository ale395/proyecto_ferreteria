<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = 
    ['codigo', 'nombre','apellido','ruc','nro_documento','telefono','direccion','correo','activo'];

    
}
