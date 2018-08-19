<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'lineas';

    protected $fillable = ['num_linea', 'descripcion'];

    
}
