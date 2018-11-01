<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AjusteInventarioCab extends Model
{
  
        CONST MAX_LINEAS_DETALLE = 3;
    
        protected $table = 'ajustes_inventarios_cab';
        protected $primaryKey = 'id';
        protected $fillable = [
            'nro_ajuste', 'empleado_id', 'sucursal_id', 'concepto_ajuste','fecha_emision','motivo',
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
    
        public function setEmpleadoId($empleado_id){
            $this->empleado_id = $empleado_id;
        }
        public function setConceptoAjusteId($concepto_ajuste_id){
            $this->concepto_ajuste_id = $concepto_ajuste_id;
        }
    
        public function setSucursalId($sucursal_id){
            $this->sucursal_id = $sucursal_id;
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

        public function conceptoAjuste()
        {
            return $this->belongsTo('App\ConceptoAjuste');
        }
    
        public function empleado()
        {
            return $this->belongsTo('App\Empleado');
        }
    
        public function sucursal()
        {
            return $this->belongsTo('App\Sucursal');
        }
    
        public function ajustesInventariosDetalle(){
            return $this->hasMany('App\AjusteInventarioDet', 'ajuste_inventario_cab_id');
        }
    
    }
    
