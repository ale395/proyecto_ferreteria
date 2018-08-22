<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormaDePago extends Model
{
    protected $table = 'formas_pagos';
    
    protected $fillable = 
    ['codigo','descripcion','control_valor'];


}
