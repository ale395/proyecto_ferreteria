<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $table = 'series';

    protected $fillable = [
        'tipo_comprobante', 'serie', 'timbrado_id', 'nro_inicial', 'nro_final', 'nro_actual', 'activo',
    ];

    public function timbrado()
    {
        return $this->belongsTo('App\Timbrado');
    }
}
