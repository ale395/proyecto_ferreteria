<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CobranzaDet extends Model
{
    protected $table = 'cobranza_det';

    public function setCobranzaCabId($cobranza_cab_id){
    	$this->cobranza_cab_id = $cobranza_cab_id;
    }

    public function setFormaPagoId($forma_pago_id){
    	$this->forma_pago_id = $forma_pago_id;
    }

    public function setBancoId($banco_id){
        $this->banco_id = $banco_id;
    }

    public function setNroValor($nro_valor){
    	$this->numero_valor = $nro_valor;
    }

    public function getNroValor(){
    	return $this->numero_valor;
    }

    public function setFechaEmision($fecha){
    	$this->fecha_emision = $fecha;
    }

    public function getFechaEmision(){
    	return date("d-m-Y", strtotime($this->fecha_emision));
    }

    public function setMonto($monto){
    	$this->monto = $monto;
    }

    public function getMonto(){
    	return $this->monto;
    }

    public function getMontoIndex(){
        return number_format($this->monto, 0, ',', '.');
    }

    public function formaPago()
    {
        return $this->belongsTo('App\FormaPago', 'forma_pago_id');
    }

    public function banco()
    {
        return $this->belongsTo('App\Banco', 'banco_id', 'id');
    }
}
