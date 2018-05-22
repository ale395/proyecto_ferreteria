<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cajero extends Model
{
    protected $table = 'cajeros';
    
    protected $fillable = [
        'num_cajero','descripcion', 'id_usuario',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}


