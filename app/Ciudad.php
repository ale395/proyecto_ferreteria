<?php

namespace App;

use App\Pais;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades';
    
    protected $fillable = [
        'descripcion', 'pais_id',
    ];

    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }
}

