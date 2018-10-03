<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatosDefault extends Model
{
    protected $table = 'datos_default';
    
    protected $fillable = ['moneda_nacional_id','lista_precio_id'];

    public function moneda(){
    	return $this->belongsTo('App\Moneda', 'moneda_nacional_id');
    }

    public function listaPrecio(){
    	return $this->belongsTo('App\ListaPrecioCabecera', 'lista_precio_id');
    }
}
