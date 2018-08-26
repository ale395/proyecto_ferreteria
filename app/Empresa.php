<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';

    protected $fillable = [
        'razon_social', 'ruc', 'direccion', 'correo_electronico', 'sitio_web', 'eslogan', 'telefono', 'rubro',
    ];
}
