<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CobranzaCab extends Model
{
    protected $table = 'cobranza_cab';

    public function getId(){
        return $this->id;
    }

    public function getFecha(){
    	return $this->fecha;
    }

    public function setFecha($fecha){
    	$this->fecha = $fecha;
    }

    public function setSucursalId($sucursal_id){
    	$this->sucursal_id = $sucursal_id;
    }

    public function setHabilitacionId($habilitacion_id){
    	$this->habilitacion_id = $habilitacion_id;
    }

    public function setMonedaId($moneda_id){
    	$this->moneda_id = $moneda_id;
    }

    public function setValorCambio($valor_cambio){
    	$this->valor_cambio = $valor_cambio;
    }

    public function getValorCambio(){
    	return $this->valor_cambio;
    }

    public function setClienteId($cliente_id){
    	$this->cliente_id = $cliente_id;
    }

    public function setEstado($estado){
    	$this->estado = $estado;
    }

    public function getEstadoIndex(){
    	if ($this->estado == 'R') {
    		return 'Realizado';
    	} elseif ($this->estado == 'A') {
    		return 'Anulado';
    	}
    }

    public function getEstado(){
    	return $this->estado;
    }

    public function setComentario($comentario){
    	$this->comentario = $comentario;
    }

    public function getComentario(){
    	return $this->comentario;
    }

    public function setMontoTotal($monto_total){
    	$this->monto_total = $monto_total;
    }

    public function getMontoTotal(){
    	return $this->monto_total;
    }

    public function setVuelto($vuelto){
        $this->vuelto = $vuelto;
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal', 'sucursal_id');
    }

    public function habilitacion()
    {
        return $this->belongsTo('App\HabilitacionCaja', 'habilitacion_id');
    }

    public function moneda()
    {
        return $this->belongsTo('App\Moneda', 'moneda_id');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'cliente_id');
    }
}
