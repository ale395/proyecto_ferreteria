<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClasificacionCliente extends Model
{
    protected $table = 'tipos_clientes';

    protected $fillable = ['codigo', 'nombre'];
}

