<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';

    protected $fillable = ['codigo', 'usuario_id', 'activo'];

    public function usuario()
    {
        return $this->belongsTo('App\User');
    }
}
