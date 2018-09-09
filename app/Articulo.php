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
}
