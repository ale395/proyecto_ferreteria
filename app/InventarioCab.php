<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventarioCab extends Model
{
  
        CONST MAX_LINEAS_DETALLE = 3;
    
        protected $table = 'inventarios_cab';
        protected $primaryKey = 'id';
        protected $fillable = [
            'fecha_emision','nro_inventario','sucursal_id','motivo','usuario'
        ];
    
        public function getId(){
            return $this->id;
        }
    
        public function setNroInventario($nro_inventario){
            $this->nro_inventario = $nro_inventario;
        }
    
        public function getNroInventario(){
            return $this->nro_inventario;
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

        public function usuario()
        {
            return $this->belongsTo('App\User');
        }

        public function sucursal()
        {
            return $this->belongsTo('App\Sucursal');
        }
    
        public function inventarioDetalle(){
            return $this->hasMany('App\InventarioDet', 'inventario_cab_id');
        }
    
    }
    
