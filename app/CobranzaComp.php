<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CobranzaComp extends Model
{
    protected $table = 'cobranza_comp';

    /*public function setTipoComprobante($tipo_comprobante){
    	$this->tipo_comp = $tipo_comprobante;
    }

    public function getTipoComprobante(){
    	return $this->tipo_comp;
    }*/
    public function getId(){
    	return $this->id;
    }

    public function setComprobanteId($comprobante_id){
    	$this->comp_id = $comprobante_id;
    }

    public function setCobranzaCabId($cobranza_cab_id){
    	$this->cobranza_cab_id = $cobranza_cab_id;
    }

    public function setMonto($monto){
    	$this->monto = $monto;
    }

    public function getMonto(){
    	return $this->monto;
    }
}
