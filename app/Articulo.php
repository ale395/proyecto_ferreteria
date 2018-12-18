<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulos';

    protected $fillable = 
    ['img_producto','codigo','descripcion','codigo_barra','porcentaje_ganancia',
    'comentario','ultimo_costo','costo_promedio','control_existencia','vendible','activo',
   'impuesto_id','rubro_id','familia_id','linea_id','unidad_medida_id','fecha_ultima_compra'];

    public function getId(){
        return $this->id;
    }


    public function getComentario(){
        return $this->comentario;
    }
    public function setComentario($comentario){
    	$this->comentario = $comentario;
    }
    public function getImpuestoId(){
        return $this->impuesto_id;
    }

    public function getCodigo(){
        return $this->codigo;
    }
    
    public function setCodigo($codigo){
    	$this->codigo = $codigo;
    }
    public function setImpuestoId($impuesto_id){
    	$this->impuesto_id = $impuesto_id;
    }
    public function setRubroId($rubro_id){
    	$this->rubro_id = $rubro_id;
    }
    public function setFamiliaId($familia_id){
    	$this->familia_id = $familia_id;
    }
    public function setLineaId($linea_id){
    	$this->linea_id = $linea_id;
    }
    public function setUnidadMedidaId($unidad_medida_id){
    	$this->unidad_medida_id = $unidad_medida_id;
    }
    public function getDescripcion(){
        return $this->descripcion;
    }
    public function setDescripcion($descripcion){
    	$this->descripcion = $descripcion;
    }

    public function getCodigoBarra(){
        return $this->codigo_barra;
    }
    public function setCodigoBarra($codigo_barra){
    	$this->codigo_barra = $codigo_barra;
    }

    public function getPorcentajeGanancia(){
        return $this->porcentaje_ganancia;
    }
    public function setPorcentajeGanancia($porcentaje_ganancia){
    	$this->porcentaje_ganancia = $porcentaje_ganancia;
    }

    public function getActivo(){
        return $this->activo;
    }
    public function setActivo($activo){
    	$this->activo = $activo;
    }

    public function getVendible(){
        return $this->vendible;
    }
    public function setVendible($vendible){
    	$this->vendible = $vendible;
    }

    public function getControlExistencia(){
        return $this->control_existencia;
    }
    public function setControExistencia($control_existencia){
    	$this->control_existencia = $control_existencia;
    }

    public function getNombreSelect(){
        if (is_null($this->codigo_barra) || $this->codigo_barra == 0) {
            return '('.$this->codigo.') '.$this->descripcion;
        } else {
            return '('.$this->codigo_barra.') '.$this->descripcion;
        }
    }

    public function impuesto()
    {
        return $this->belongsTo('App\Impuesto');
    }
    public function rubro()
    {
        return $this->belongsTo('App\Rubro');
    }
    public function familia()
    {
        return $this->belongsTo('App\Familia');
    }
    public function linea()
    {
        return $this->belongsTo('App\Linea');
    }
    public function unidadMedida()
    {
        return $this->belongsTo('App\UnidadMedida');
    }

    public function getUltimoCosto(){
        return number_format($this->ultimo_costo, 0, ',', '.');
    }

    public function setUltimoCosto($ultimo_costo){
    	$this->ultimo_costo = $ultimo_costo;
    }
    public function getCostoPromedio(){
        return number_format($this->costo_promedio, 0, ',', '.');
    }
    public function setCostoPromedio($costo_promedio){
    	$this->costo_promedio = $costo_promedio;
    }
    
}
