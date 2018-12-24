<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaCreditoComprasDet extends Model
{
    protected $table = 'nota_credito_compras_det';

    public function setNotaCreditoCabeceraId($nota_credito_cab_id){
        $this->nota_credito_cab_id = $nota_credito_cab_id;
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

    public function setPorcentajeDescuento($porcentaje_descuento){
        $this->porcentaje_descuento = $porcentaje_descuento;
    }

    public function getPorcentajeDescuento(){
        return $this->porcentaje_descuento;
    }

    public function setMontoDescuento($monto_descuento){
        $this->monto_descuento = $monto_descuento;
    }

    public function getMontoDescuento(){
        return number_format($this->monto_descuento, 0, ',', '.');
    }

    public function setPorcentajeIva($porcentaje_iva){
        $this->porcentaje_iva = $porcentaje_iva;
    }

    public function getPorcentajeIva(){
        return $this->porcentaje_iva;
    }

    public function setMontoExenta($monto_exenta){
        $this->monto_exenta = $monto_exenta;
    }

    public function getMontoExenta(){
        return number_format($this->monto_exenta, 0, ',', '.');
    }

    public function setMontoGravada($monto_gravada){
        $this->monto_gravada = $monto_gravada;
    }

    public function getMontoGravada(){
        return number_format($this->monto_gravada, 0, ',', '.');
    }

    public function setMontoIva($monto_iva){
        $this->monto_iva = $monto_iva;
    }

    public function getMontoIva(){
        return number_format($this->monto_iva, 0, ',', '.');
    }

    public function setMontoTotal($monto_total){
        $this->monto_total = $monto_total;
    }

    public function getMontoTotal(){
        return number_format($this->monto_total, 0, ',', '.');
    }

    public function articulo()
    {
        return $this->belongsTo('App\Articulo');
    }

    public function notaCreditoCabecera()
    {
        return $this->belongsTo('App\NotaCreditoComprasCab');
    }
}
