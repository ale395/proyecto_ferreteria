<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    protected $table = 'conceptos';

    protected $fillable = [
        'id', 'concepto', 'nombre_concepto','modulo_id', 'tipo_concepto','muev_stock',
    ];

    public function modulo()
    {
        return $this->belongsTo('App\Modulo');
    }
}
