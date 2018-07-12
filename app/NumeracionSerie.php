<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NumeracionSerie extends Model
{
    protected $table = 'numeracion_series';

    protected $fillable = [
        'concepto_id', 'serie_id', 'nro_inicial', 'nro_final', 'estado',
    ];

    public function concepto()
    {
        return $this->belongsTo('App\Concepto');
    }

    public function serie()
    {
        return $this->belongsTo('App\Serie');
    }
}
