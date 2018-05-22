<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClasificacionCliente extends Model
{
    protected $table = 'clasificacion_clientes';

    protected $fillable = ['num_clasif_cliente', 'descripcion'];
}

