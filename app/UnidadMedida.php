<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $table = 'unidad_medidas';

    protected $fillable = ['num_umedida', 'descripcion'];
}
