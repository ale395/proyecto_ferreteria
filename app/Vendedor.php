<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
<<<<<<< HEAD
    //
=======
    protected $table = 'vendedores';

    protected $fillable = ['codigo', 'usuario_id', 'activo'];

    public function usuario()
    {
        return $this->belongsTo('App\User');
    }
>>>>>>> 5d5b68b41abeab7bba4dcb702fb12fd689e26a33
}
