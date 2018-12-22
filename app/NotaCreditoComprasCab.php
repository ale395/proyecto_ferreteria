<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaCreditoComprasCab extends Model
{
    CONST MAX_LINEAS_DETALLE = 10;

    protected $table = 'nota_credito_compras_cab';

    public function getId(){
        return $this->id;
    }

    public function getTipoNotaCredito(){
    	return $this->tipo_nota_credito;
    }

    public function getTipoNotaCreditoIndex(){
        if ($this->tipo_nota_credito == 'DV') {
            return 'Devolución';
        } elseif ($this->tipo_nota_credito == 'DC') {
            return 'Descuento';
        }
    }

    public function setFacturaId($compra_cab_id){
        $this->compra_cab_id = $compra_cab_id;
    }

    public function setTipoNotaCredito($tipo_nota_credito){
    	$this->tipo_nota_credito = $tipo_nota_credito;
    }

    public function getNroNotaCredito(){
    	return $this->nro_nota_credito;
    }

    public function getNroNotaCreditoIndex(){
        return $this->nro_nota_credito;
    }

    public function setNroNotaCredito($nro_nota_credito){
        $this->nro_nota_credito = $nro_nota_credito;
    }

    public function setProveedorId($proveedor_id){
        $this->proveedor_id = $proveedor_id;
    }

    public function setSucursalId($sucursal_id){
        $this->sucursal_id = $sucursal_id;
    }

    public function setMonedaId($moneda_id){
        $this->moneda_id = $moneda_id;
    }

    public function setUsuarioId($usuario_id){
        $this->usuario_id = $usuario_id;
    }

    public function getValorCambio(){
        return number_format($this->valor_cambio, 0, ',', '.');
    }

    public function setValorCambio($valor_cambio){
        $this->valor_cambio = $valor_cambio;
    }

    public function getFechaEmision(){
    	return date("d-m-Y", strtotime($this->fecha_emision));
    }

    public function setFechaEmision($fecha_emision){
        $this->fecha_emision = $fecha_emision;
    }

    //fecha_vigencia_timbrado
    public function setFechaVigenciaTimbrado($fecha_vigencia_timbrado){
        $this->fecha_vigencia_timbrado = $fecha_vigencia_timbrado;
    }

    public function getFechaVigenciaTimbrado(){
        return date("d-m-Y", strtotime($this->fecha_vigencia_timbrado));
    }

    //monto total
    public function getMontoTotal(){
        return number_format($this->monto_total, 0, ',', '.');
    }

    public function getMontoTotalNumber(){
        return $this->monto_total;
    }

    public function setMontoTotal($monto_total){
        $this->monto_total = $monto_total;
    }

    //total exenta
    public function getMontoTotalExenta(){
        return number_format($this->total_exenta, 0, ',', '.');
    }

    public function getMontoTotalExentaNumber(){
        return $this->total_exenta;
    }

    public function setMontoTotalExenta($total_exenta){
        $this->total_exenta = $total_exenta;
    }

    //total gravada
    public function getMontoTotalGravada(){
        return number_format($this->total_gravada, 0, ',', '.');
    }

    public function getMontoTotalGravadaNumber(){
        return $this->total_gravada;
    }

    public function setMontoTotalGravada($total_gravada){
        $this->total_gravada = $total_gravada;
    }

    //total iva
    public function getMontoTotalIVA(){
        return number_format($this->total_iva, 0, ',', '.');
    }

    public function getMontoTotalIVANumber(){
        return $this->total_iva;
    }

    public function setMontoTotalIVA($total_iva){
        $this->total_iva = $total_iva;
    }


    public function getComentario(){
        return $this->comentario;
    }

    public function setComentario($comentario){
        $this->comentario = $comentario;
    }

    public function getEstado(){
        return $this->estado;
    }

    public function getEstadoNombre(){
        if ($this->estado == 'P') {
            return 'Cancelada';
        } elseif ($this->estado == 'A') {
            return 'Anulada';
        }
    }

    public function setEstado($estado){
        $this->estado = $estado;
    }

    public function setCompraId($compras_cab_id){
        $this->compras_cab_id = $compras_cab_id;
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }


    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal');
    }

    public function moneda()
    {
        return $this->belongsTo('App\Moneda');
    }

    public function usuario()
    {
        return $this->belongsTo('App\User');
    }

    public function notaCreditoDetalle(){
        return $this->hasMany('App\NotaCreditoComprasDet', 'nota_credito_cab_id');
    }

    public function compra()
    {
        return $this->hasOne('App\ComprasCab', 'id', 'compras_cab_id');
    }
}
