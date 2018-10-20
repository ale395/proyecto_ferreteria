<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoVentaCab extends Model
{
    CONST MAX_LINEAS_DETALLE = 3;

    protected $table = 'pedidos_ventas_cab';

    protected $fillable = [
        'nro_pedido', 'cliente_id', 'sucursal_id', 'moneda_id', 'valor_cambio', 'fecha_emision', 'monto_total', 'estado','comentario',
    ];

    public function getId(){
        return $this->id;
    }

    public function setNroPedido($nro_pedido){
        $this->nro_pedido = $nro_pedido;
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

    public function setValorCambio($valor_cambio){
        $this->valor_cambio = $valor_cambio;
    }

    public function setFechaEmision($fecha_emision){
        $this->fecha_emision = $fecha_emision;
    }

    public function setMontoTotal($monto_total){
        $this->monto_total = $monto_total;
    }

    public function setComentario($comentario){
        $this->comentario = $comentario;
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal');
    }

    public function moneda()
    {
        return $this->belongsTo('App\Moneda');
    }

}
