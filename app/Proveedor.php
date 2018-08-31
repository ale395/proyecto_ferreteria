<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = 
    ['codigo', 'nombre','razon_social','ruc','nro_documento',
    'telefono','direccion','correo','tipo_vendedor_id','activo'];
    
}
