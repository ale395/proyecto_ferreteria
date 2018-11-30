<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AjusteInventarioCab extends Model
{
  
        CONST MAX_LINEAS_DETALLE = 3;
    
        protected $table = 'ajustes_inventarios_cab';
        protected $primaryKey = 'id';
        protected $fillable = [
            'fecha_emision','nro_ajuste','sucursal_id', 'concepto_ajuste_id','motivo','usuario'
        ];
    
        public function getId(){
            return $this->id;
        }
    
        public function setNroAjuste($nro_ajuste){
            $this->nro_ajuste = $nro_ajuste;
        }
    
        public function getNroAjuste(){
            return $this->nro_ajuste;
        }
        public function setConceptoAjusteId($concepto_ajuste_id){
            $this->concepto_ajuste_id = $concepto_ajuste_id;
        }
        public function getConceptoAjuste(){
            return $this->conceptoAjuste;
        }
    
        public function setSucursalId($sucursal_id){
            $this->sucursal_id = $sucursal_id;
        }
        public function getSucursal(){
            return $this->sucursal;
        }
    
        public function setFechaEmision($fecha_emision){
            $this->fecha_emision = $fecha_emision;
        }
    
        public function getFechaEmision(){
            return date("d-m-Y", strtotime($this->fecha_emision));
        }
        
        public function setMotivo($motivo){
            $this->motivo = $motivo;
        }
    
        public function getMotivo(){
            return $this->motivo;
        }

        public function getMontoTotal(){
            return number_format($this->monto_total, 0, ',', '.');
        }
    
        public function setMontoTotal($monto_total){
            $this->monto_total = $monto_total;
        }

        public function setUsuarioId($usuario_id){
            $this->usuario_id = $usuario_id;
        }

        public function conceptoAjuste()
        {
            return $this->belongsTo('App\ConceptoAjuste');
        }

        public function usuario()
        {
            return $this->belongsTo('App\User');
        }

        public function sucursal()
        {
            return $this->belongsTo('App\Sucursal');
        }
    
        public function ajusteInventarioDetalle(){
            return $this->hasMany('App\AjusteInventarioDet', 'ajuste_inventario_cab_id');
        }
    
    }
    
