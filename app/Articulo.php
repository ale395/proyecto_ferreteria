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

    public function getCodigo(){
        return $this->codigo;
    }

    public function getDescripcion(){
        return $this->descripcion;
    }

    public function getCodigoBarra(){
        return $this->codigo_barra;
    }

    public function getActivo(){
        return $this->activo;
    }

    public function getVendible(){
        return $this->vendible;
    }

    public function getControlExistencia(){
        return $this->control_existencia;
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
        return number_format($this->ultimo_costo, 2, ',', '.');
    }

    public function getCostoPromedio(){
        return number_format($this->costo_promedio, 2, ',', '.');
    }
}
