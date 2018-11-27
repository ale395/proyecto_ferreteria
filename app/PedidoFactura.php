<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoFactura extends Model
{
    protected $table = 'pedidos_facturas';

    public function setPedidoId($pedido_id){
    	$this->pedido_cabecera_id = $pedido_id;
    }

    public function setFacturaId($factura_id){
    	$this->factura_cabecera_id = $factura_id;
    }

    public function pedido()
    {
        return $this->belongsTo('App\PedidoVentaCab');
    }

    public function factura()
    {
        return $this->belongsTo('App\FacturaVentaCab');
    }
}
