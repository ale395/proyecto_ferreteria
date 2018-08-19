<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListaPrecioCabecera extends Model
{
    protected $table = 'lista_precios_cabecera';

    protected $fillable = ['codigo', 'nombre', 'moneda_id'];

    public function moneda()
    {
        return $this->belongsTo('App\Moneda');
    }
}
