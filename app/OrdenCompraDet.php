<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenCompraDet extends Model
{
    protected $table = 'orden_compras_det';

    protected $fillable = [
        'orden_compra_cab_id', 
        'articulo_id', 
        'cantidad', 
        'costo_unitario', 
        'costo_promedio', 
        'sub_total',
        'porcentaje',
        'total_exenta',
        'total_gravada',
        'total_iva'
    ];

    public function setPedidoCabeceraId($pedido_cabecera_id){
        $this->pedido_cab_id = $pedido_cabecera_id;
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

    public function getCantidadNumber(){
        return number_format($this->cantidad, 2, ',', '.');
    }

    public function setCostoUnitario($costo_unitario){
        $this->costo_unitario = $costo_unitario;
    }

    public function getCostoUnitario(){
        return number_format($this->costo_unitario, 0, ',', '.');
    }

    public function setCostoPromedio($costo_promedio){
        $this->costo_promedio = $costo_promedio;
    }

    public function getCostoPRomedio(){
        return $this->costo_promedio;
    }

    public function setPorcentajeIva($porcentaje_iva){
        $this->porcentaje_iva = $porcentaje_iva;
    }

    public function getPorcentajeIva(){
        return $this->porcentaje_iva;
    }

    public function sentTotalExenta($total_exenta){
        $this->total_exenta = $total_exenta;
    }

    public function getTotalExenta(){
        return number_format($this->total_exenta, 0, ',', '.');
    }

    public function setTotalGravada($total_gravada){
        $this->total_gravada = $total_gravada;
    }

    public function getTotalGravada(){
        return number_format($this->total_gravada, 0, ',', '.');
    }

    public function setTotalIva($total_iva){
        $this->total_iva = $total_iva;
    }

    public function getTotalIva(){
        return number_format($this->total_iva, 0, ',', '.');
    }

    public function setSubtotal($sub_total){
        $this->sub_total = $sub_total;
    }

    public function getSubTotal(){
        return number_format($this->sub_total, 0, ',', '.');
    }

    public function articulo()
    {
        return $this->belongsTo('App\Articulo');
    }

    public function pedidoCabecera()
    {
        return $this->belongsTo('App\OrdenCompraCab');
    }
}
