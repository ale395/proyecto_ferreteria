<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaCreditoVentaCab extends Model
{
    CONST MAX_LINEAS_DETALLE = 10;

    protected $table = 'nota_credito_ventas_cab';

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

    public function setTipoNotaCredito($tipo_nota_credito){
    	$this->tipo_nota_credito = $tipo_nota_credito;
    }

    public function getNroNotaCredito(){
    	return $this->nro_nota_credito;
    }

    public function getNroNotaCreditoIndex(){
        $configuracion_empresa = Empresa::first();
        $serie = $configuracion_empresa->getCodigoEstablecimiento().'-'.$this->sucursal->getCodigoPuntoExpedicion();
        return $serie.' '.str_pad($this->nro_nota_credito, 7, "0", STR_PAD_LEFT);
    }

    public function setNroNotaCredito($nro_nota_credito){
        $this->nro_nota_credito = $nro_nota_credito;
    }

    public function setSerieId($serie_id){
        $this->serie_id = $serie_id;
    }

    public function setClienteId($cliente_id){
        $this->cliente_id = $cliente_id;
    }

    public function setSucursalId($sucursal_id){
        $this->sucursal_id = $sucursal_id;
    }

    public function setMonedaId($moneda_id){
        $this->moneda_id = $moneda_id;
    }

    public function setListaPrecioId($lista_precio_id){
        $this->lista_precio_id = $lista_precio_id;
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

    public function getMontoTotal(){
        return number_format($this->monto_total, 0, ',', '.');
    }

    public function getMontoTotalNumber(){
        return $this->monto_total;
    }

    public function setMontoTotal($monto_total){
        $this->monto_total = $monto_total;
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

    public function setFacturaId($factura_cab_id){
        $this->factura_cab_id = $factura_cab_id;
    }

    public function serie()
    {
        return $this->belongsTo('App\Serie');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function listaPrecio()
    {
        return $this->belongsTo('App\ListaPrecioCabecera', 'lista_precio_id');
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
        return $this->hasMany('App\NotaCreditoVentaDet', 'nota_credito_cab_id');
    }

    public function factura()
    {
        return $this->hasOne('App\FacturaVentaCab', 'id', 'factura_cab_id');
    }
}
