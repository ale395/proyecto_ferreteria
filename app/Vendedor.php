<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
<<<<<<< HEAD
=======

>>>>>>> a9dd6d6d500e891b45f69a9b00d1acd8ac4763e7
    protected $table = 'vendedores';

    protected $fillable = ['codigo', 'usuario_id', 'activo'];

    public function usuario()
    {
        return $this->belongsTo('App\User');
    }
<<<<<<< HEAD
=======

>>>>>>> a9dd6d6d500e891b45f69a9b00d1acd8ac4763e7
}
