<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnulacionComprobante extends Model
{
    protected $table = 'anulacion_comprobantes';

    protected $fillable = [
    	'tipo_comprobante', 'comprobante_id', 'motivo_anulacion_id', 'fecha_anulacion',
    ];

    public function getTipoComprobante(){
    	return $this->tipo_comprobante;
    }

    public function getTipoComprobanteName(){
    	if ($this->tipo_comprobante == 'F') {
    		return 'Factura';
    	} elseif ($this->tipo_comprobante == 'N') {
    		return 'Nota de Crédito';
    	}
    }

    public function getFechaAnulacion(){
    	return date("d-m-Y", strtotime($this->fecha_anulacion));
    }

    public function setFechaAnulacion($fecha_anulacion){
    	$this->fecha_anulacion = $fecha_anulacion;
    }

    public function setTipoComprobante($tipo_comprobante){
    	$this->tipo_comprobante = $tipo_comprobante;
    }

    public function setComprobanteId($comprobante_id){
        $this->comprobante_id = $comprobante_id;
    }

    public function setMotivoAnulacionId($motivo_id){
        $this->motivo_anulacion_id = $motivo_id;
    }

    public function factura(){
    	return $this->belongsTo('App\FacturaVentaCab', 'comprobante_id');
    }

    public function notaCredito(){
    	return $this->belongsTo('App\NotaCreditoVentaCab', 'comprobante_id');
    }
}
