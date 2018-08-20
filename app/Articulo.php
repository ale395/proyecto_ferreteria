<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulos';

    protected $fillable = ['id','codigo','descripcion','codigo_barra','costo','control_existencia','vendible','activo'];
}
