<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    protected $table = 'rubros';

    protected $fillable = ['num_rubro', 'descripcion'];
}
