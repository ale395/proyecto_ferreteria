<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';

    protected $fillable = ['codigo', 'nombre', 'usuario_id', 'activo'];
}
