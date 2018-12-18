<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExistenciaArticulo extends Model
{
    protected $table = 'existencia_articulos';

    public function getCantidad(){
    	return number_format($this->cantidad, 0, ',', '.');
    }

    public function getCantidadNumber(){
        return $this->cantidad;
    }

    public function setCantidad($cantidad){
    	$this->cantidad = $cantidad;
    }

    public function setArticuloId($articulo_id){
    	$this->articulo_id = $articulo_id;
    }

    public function setSucursalId($sucursal_id){
    	$this->sucursal_id = $sucursal_id;
    }

    public function actualizaStock($operacion, $cantidad){
        if ($operacion == '+') {
            $this->cantidad = $this->cantidad + $cantidad;
        } elseif ($operacion = '-') {
            $this->cantidad = $this->cantidad - $cantidad;
        }
    }

    public function setFechaUltimoInventario($fecha_ultimo_inventario){
        $this->fecha_ultimo_inventario = $fecha_ultimo_inventario;
    }

    public function getFechaUltimoInventario(){
        return date("d-m-Y", strtotime($this->fecha_ultimo_inventario));
    }

    public function articulo()
    {
        return $this->belongsTo('App\Articulo', 'articulo_id');
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal', 'sucursal_id');
    }
}
