<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulos';

    protected $fillable = 
    ['codigo','descripcion','codigo_barra','costo','control_existencia','vendible','activo'];
}
