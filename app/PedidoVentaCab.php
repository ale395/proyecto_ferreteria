<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoVentaCab extends Model
{
    CONST MAX_LINEAS_DETALLE = 3;

    protected $table = 'pedidos_ventas_cab';

    protected $fillable = [
        'nro_pedido', 'cliente_id', 'sucursal_id', 'moneda_id', 'lista_precio_id', 'valor_cambio', 'fecha_emision', 'monto_total', 'estado','comentario',
    ];

    public function getId(){
        return $this->id;
    }

    public function setNroPedido($nro_pedido){
        $this->nro_pedido = $nro_pedido;
    }

    public function getNroPedido(){
        return $this->nro_pedido;
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

    public function setValorCambio($valor_cambio){
        $this->valor_cambio = $valor_cambio;
    }

    public function getValorCambio(){
        return number_format($this->valor_cambio, 0, ',', '.');
    }

    public function setFechaEmision($fecha_emision){
        $this->fecha_emision = $fecha_emision;
    }

    public function getFechaEmision(){
        return date("d-m-Y", strtotime($this->fecha_emision));
    }

    public function setMontoTotal($monto_total){
        $this->monto_total = $monto_total;
    }

    public function getMontoTotal(){
        return number_format($this->monto_total, 0, ',', '.');
    }

    public function setComentario($comentario){
        $this->comentario = $comentario;
    }

    public function getComentario(){
        return $this->comentario;
    }

    public function getEstado(){
        return $this->estado;
    }

    public function getEstadoNombre(){
        if ($this->estado == 'P') {
            return 'Pendiente';
        } elseif ($this->estado == 'F') {
            return 'Facturado';
        } elseif ($this->estado == 'C') {
            return 'Cancelado';
        } elseif ($this->estado == 'V') {
            return 'Vencido';
        }
    }

    public function setEstado($estado){
        $this->estado = $estado;
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

    public function pedidosDetalle(){
        return $this->hasMany('App\PedidoVentaDet', 'pedido_cab_id');
    }

    public function pedidoFactura(){
        return $this->hasOne('App\PedidoFactura', 'pedido_cabecera_id', 'id');
    }

    public function factura(){
        return $this->belongsToMany('App\FacturaVentaCab', 'pedidos_facturas');
    }

}
