<?php

namespace App;

use App\Empresa;
use App\CuentaCliente;
use Illuminate\Database\Eloquent\Model;

class FacturaVentaCab extends Model
{
    CONST MAX_LINEAS_DETALLE = 10;

    protected $table = 'facturas_ventas_cab';

    public function getId(){
        return $this->id;
    }

    public function getTipoFactura(){
    	return $this->tipo_factura;
    }

    public function getTipoFacturaIndex(){
        if ($this->tipo_factura == 'CO') {
            return 'Contado';
        } elseif ($this->tipo_factura == 'CR') {
            return 'Crédito';
        }
    }

    public function setTipoFactura($tipo_factura){
    	$this->tipo_factura = $tipo_factura;
    }

    public function getNroFactura(){
    	return $this->nro_factura;
    }

    public function getNroFacturaIndex(){
        $configuracion_empresa = Empresa::first();
        $serie = $configuracion_empresa->getCodigoEstablecimiento().'-'.$this->sucursal->getCodigoPuntoExpedicion();
        return $serie.' '.str_pad($this->nro_factura, 7, "0", STR_PAD_LEFT);
    }

    public function setNroFactura($nro_factura){
        $this->nro_factura = $nro_factura;
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
            return 'Pendiente';
        } elseif ($this->estado == 'C') {
            return 'Cobrada';
        } elseif ($this->estado == 'A') {
            return 'Anulada';
        }
    }

    public function getMontoSaldo(){
        $cuenta_cliente = CuentaCliente::where('tipo_comprobante', 'F')
            ->where('comprobante_id', $this->id)->first();
        return $cuenta_cliente->getMontoSaldo();
    }

    public function getMontoSaldoFormat(){
        $cuenta_cliente = CuentaCliente::where('tipo_comprobante', 'F')
            ->where('comprobante_id', $this->id)->first();
        return number_format($cuenta_cliente->getMontoSaldo(), 0, ',', '.');
    }

    public function setEstado($estado){
        $this->estado = $estado;
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

    public function facturaDetalle(){
        return $this->hasMany('App\FacturaVentaDet', 'factura_cab_id');
    }

    public function facturaPedidos(){
        return $this->hasMany('App\PedidoFactura', 'factura_cabecera_id', 'id');
    }

    public function pedidos(){
        return $this->belongsToMany('App\PedidoVentaCab', 'pedidos_facturas', 'factura_cabecera_id', 'pedido_cabecera_id');
    }
}
