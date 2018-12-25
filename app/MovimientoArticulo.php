<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovimientoArticulo extends Model
{
    protected $table = 'movimientos_articulos';
    
    protected $fillable = ['tipo_movimiento','movimiento_id','fecha_movimiento', 'articulo_id','sucursal_id','cantidad'];

    public function setTipoMovimiento($tipo_movimiento){
    	$this->tipo_movimiento = $tipo_movimiento;
    }

    public function setMovimientoId($movimiento_id){
    	$this->movimiento_id = $movimiento_id;
    }

    public function setFecha($fecha_movimiento){
    	$this->fecha_movimiento = $fecha_movimiento;
    }
    public function setArticuloId($articulo_id){
        $this->articulo_id = $articulo_id;
    }
    public function setSucursalId($sucursal_id){
        $this->sucursal_id = $sucursal_id;
    }
    public function setCantidad($cantidad){
        $this->cantidad = $cantidad;
    }


    public function factura(){
    	return $this->belongsTo('App\FacturaVentaCab', 'movimiento_id');
    }

    public function notaCredito(){
    	return $this->belongsTo('App\NotaCreditoVentaCab', 'movimiento_id');
    }

    public function inventario(){
    	return $this->belongsTo('App\InventarioCab', 'movimiento_id');
    }

    public function ajuste(){
    	return $this->belongsTo('App\AjusteInventarioCab', 'movimiento_id');
    }

    public function articulo(){
        return $this->belongsTo('App\Articulo', 'articulo_id');
    }
    public function sucursal(){
        return $this->belongsTo('App\Sucursal', 'sucursal_id');
    }
}
