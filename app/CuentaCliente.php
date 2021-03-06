<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CuentaCliente extends Model
{
    protected $table = 'cuenta_clientes';
    
    protected $fillable = ['tipo_comprobante','comprobante_id', 'cliente_id','monto_comprobante', 'monto_saldo'];

    public function setTipoComprobante($tipo_comprobante){
    	$this->tipo_comprobante = $tipo_comprobante;
    }

    public function setComprobanteId($comprobante_id){
    	$this->comprobante_id = $comprobante_id;
    }

    public function setClienteId($cliente_id){
        $this->cliente_id = $cliente_id;
    }

    public function setMontoComprobante($monto_comprobante){
    	$this->monto_comprobante = $monto_comprobante;
    }

    public function setMontoSaldo($monto_saldo){
    	$this->monto_saldo = $monto_saldo;
    }

    public function getMontoSaldo(){
        return $this->monto_saldo;
    }

    public function getMontoComprobante(){
        return $this->monto_comprobante;
    }

    public function factura(){
    	return $this->belongsTo('App\FacturaVentaCab', 'comprobante_id');
    }

    public function notaCredito(){
    	return $this->belongsTo('App\NotaCreditoVentaCab', 'comprobante_id');
    }

    public function cobranza(){
        return $this->belongsTo('App\CobranzaCab', 'comprobante_id');
    }

    public function cliente(){
        return $this->belongsTo('App\Cliente', 'cliente_id');
    }
}
