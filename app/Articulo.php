<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulos';

    protected $fillable = 
    ['codigo','descripcion','codigo_barra','porcentaje_ganancia',
    'comentario','costo','control_existencia','vendible','activo',
   'impuesto_id','rubro_id','familia_id','linea_id','unidad_medida_id'];

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
        return $this->ultimo_costo;
    }

    public function getCostoPromedio(){
        return $this->costo_promedio;
    }
}
