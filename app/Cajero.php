<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cajero extends Model
{
    protected $table = 'cajeros';
    
    protected $fillable = [
        'num_cajero','descripcion', 'usuario_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}


