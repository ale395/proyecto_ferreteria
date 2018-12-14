<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AjusteInventarioDet extends Model
{
    protected $table = 'ajustes_inventarios_det';

    protected $fillable = [
        'ajuste_inventario_cab_id', 'articulo_id', 'cantidad', 'monto_total','existencia'
    ];

    public function setAjusteInventarioCabId($ajuste_inventario_cab_id){
        $this->ajuste_inventario_cab_id = $ajuste_inventario_cab_id;
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


    public function ajusteInventarioCabecera()
    {
        return $this->belongsTo('App\AjusteInventarioCab');
    }
    public function articulo()
    {
        return $this->belongsTo('App\Articulo');
    }

}
