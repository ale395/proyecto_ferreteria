<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExistenciaArticulo extends Model
{
    protected $table = 'existencia_articulos';

    public function getCantidad(){
    	return number_format($this->cantidad, 0, ',', '.');
    }

    public function setCantidad($cantidad){
    	$this->cantidad = $cantidad;
    }

    public function setArticuloId($articulo_id){
    	$this->articulo_id = $articulo_id;
    }

    public function setSucursalId($sucursal_id){
    	$this->sucursal_id = $sucursal_id;
    }

    public function articulo()
    {
        return $this->belongsTo('App\Articulo', 'articulo_id');
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal', 'sucursal_id');
    }
}
