<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AjusteInventarioDet extends Model
{
    protected $table = 'ajustes_inventarios_det';

    protected $fillable = [
        'ajuste_inventario_cab_id', 'articulo_id', 'cantidad', 
    ];

    public function setPedidoCabeceraId($ajuste_inventario_cab_id){
        $this->pedido_cab_id = $ajuste_inventario_cab_id;
    }

    public function setArticuloId($articulo_id){
        $this->articulo_id = $articulo_id;
    }

    public function setCantidad($cantidad){
        $this->cantidad = $cantidad;
    }

    public function getCantidad(){
        return $this->cantidad;
    }

    
    public function setCantidadTotal($cantidad_total){
        $this->cantidad_total = $cantidad_total;
    }

    public function getCantidadTotal(){
        return number_format($this->cantidad_total, 0, ',', '.');
    }

    public function articulo()
    {
        return $this->belongsTo('App\Articulo');
    }

    public function ajusteInventarioCabecera()
    {
        return $this->belongsTo('App\AjusteInventarioCab');
    }

}
