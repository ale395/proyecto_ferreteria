<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $table = 'series';

    protected $fillable = [
        'tipo_comprobante', 'timbrado_id', 'sucursal_id', 'nro_inicial', 'nro_final', 'nro_actual', 'vendedor_id', 'activo',
    ];

    public function getTipoComprobante(){
        return $this->tipo_comprobante;
    }

    public function setTipoComprobante($tipo_comprobante){
        $this->tipo_comprobante = $tipo_comprobante;
    }

    public function getNroInicial(){
        return $this->nro_inicial;
    }

    public function setNroInicial($nro_inicial){
        $this->nro_inicial = $nro_inicial;
    }

    public function getNroActual(){
        return $this->nro_actual;
    }

    public function getNroFinal(){
        return $this->nro_final;
    }

    public function setNroFinal($nro_final){
        $this->nro_final = $nro_final;
    }

    public function getActivo(){
        return $this->activo;
    }

    public function setActivo($activo){
        $this->activo = $activo;
    }

    public function setVendedorId($empleado_id){
        $this->vendedor_id = $empleado_id;
    }

    public function vendedor()
    {
        return $this->belongsTo('App\Empleado', 'vendedor_id');
    }

    public function timbrado()
    {
        return $this->belongsTo('App\Timbrado');
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal');
    }
}
