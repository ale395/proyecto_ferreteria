<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timbrado extends Model
{
    protected $table = 'timbrados';

    protected $fillable = [
        'nro_timbrado', 'fecha_vigencia', 'estado',
    ];

}
