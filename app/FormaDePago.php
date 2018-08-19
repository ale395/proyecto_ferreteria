<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormaDePago extends Model
{
    protected $table = 'formasdepago';
    
    protected $fillable = [
        'descripcion', 'id',
    ];

    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }
}
