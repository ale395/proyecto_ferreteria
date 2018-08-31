<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulos';

    protected $fillable = 
    ['codigo','descripcion','codigo_barra','impuesto_id','costo',
    'control_existencia','vendible','activo','grupo_id','familia_id','unidad_medida_id'];


    public function impuesto()
    {
        return $this->belongsTo('App\impuestos');
    }
    public function grupo()
    {
        return $this->belongsTo('App\Grupo');
    }
    public function familia()
    {
        return $this->belongsTo('App\Familia');
    }
    public function unidadMedida()
    {
        return $this->belongsTo('App\UnidadMedida');
    }
}
