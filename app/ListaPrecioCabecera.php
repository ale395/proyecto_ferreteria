<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListaPrecioCabecera extends Model
{
    protected $table = 'lista_precios_cabecera';

    protected $fillable = ['codigo', 'nombre', 'moneda_id'];

    public function getId(){
        return $this->id;
    }

    public function getCodigo(){
        return $this->codigo;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function moneda()
    {
        return $this->belongsTo('App\Moneda');
    }

    public function listaPrecioDetalle()
    {
    	return $this->hasMany('App\ListaPrecioDetalle');
    }
}
