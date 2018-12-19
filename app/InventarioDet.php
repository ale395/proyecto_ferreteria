<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventarioDet extends Model
{
    protected $table = 'inventarios_det';

    protected $fillable = [
        'inventario_cab_id', 'articulo_id', 'cantidad', 'monto_total','existencia'
    ];

    public function seteInventarioCabId($inventario_cab_id){
        $this->inventario_cab_id = $inventario_cab_id;
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
    public function setExistencia($existencia){
        $this->existencia = $existencia;
    }

    public function getExistencia(){
        return $this->existencia;
    }
    public function setCostoUnitario($costo_unitario){
        $this->costo_unitario = $costo_unitario;
    }

    public function getCostoUnitario(){
        return number_format($this->costo_unitario, 0, ',', '.');
    }

    public function setSubtotal($sub_total){
        $this->sub_total = $sub_total;
    }

    public function getSubTotal(){
        return number_format($this->sub_total, 0, ',', '.');
    }


    public function inventarioCabecera()
    {
        return $this->belongsTo('App\InventarioCab');
    }
    public function articulo()
    {
        return $this->belongsTo('App\Articulo');
    }

}
