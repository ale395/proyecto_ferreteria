<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    protected $table = 'depositos';
    
    protected $fillable = ['codigo','descripcion','activo'];
}
