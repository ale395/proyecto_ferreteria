<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoProveedor extends Model
{
    protected $table = 'tipo_proveedores';

    protected $fillable = ['codigo', 'nombre'];

}
