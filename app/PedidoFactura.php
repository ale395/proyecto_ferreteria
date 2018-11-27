<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoFactura extends Model
{
    protected $table = 'pedidos_facturas';
    public $timestamps = false;
    protected $primaryKey = null;
	public $incrementing = false;

    public function setPedidoId($pedido_id){
    	$this->pedido_cabecera_id = $pedido_id;
    }

    public function setFacturaId($factura_id){
    	$this->factura_cabecera_id = $factura_id;
    }

    public function pedido()
    {
        return $this->hasOne('App\PedidoVentaCab', 'id', 'pedido_cabecera_id');
    }

    public function factura()
    {
        return $this->hasOne('App\FacturaVentaCab', 'id', 'factura_cabecera_id');
    }
}
