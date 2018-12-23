<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenPago extends Model
{
    protected $table = 'orden_pago';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'nro_orden', 
        'proveedor_id', 
        'sucursal_id', 
        'moneda_id', 
        'valor_cambio', 
        'fecha_emision', 
        'monto_total', 
        'estado',
    ];

    public function getId(){
        return $this->id;
    }

    public function setNroPedido($nro_orden){
        $this->nro_orden = $nro_orden;
    }

    public function getNroPedido(){
        return $this->nro_orden;
    }

    public function setProveedorId($proveedor_id){
        $this->proveedor_id = $proveedor_id;
    }

    public function setSucursalId($sucursal_id){
        $this->sucursal_id = $sucursal_id;
    }

    public function setMonedaId($moneda_id){
        $this->moneda_id = $moneda_id;
    }

    public function setValorCambio($valor_cambio){
        $this->valor_cambio = $valor_cambio;
    }

    public function getValorCambio(){
        return number_format($this->valor_cambio, 0, ',', '.');
    }

    public function setFechaEmision($fecha_emision){
        $this->fecha_emision = $fecha_emision;
    }

    public function getFechaEmision(){
        return date("d-m-Y", strtotime($this->fecha_emision));
    }

    public function setMontoTotal($monto_total){
        $this->monto_total = $monto_total;
    }

    public function getMontoTotal(){
        return number_format($this->monto_total, 0, ',', '.');
    }

    public function setComentario($comentario){
        $this->comentario = $comentario;
    }

    public function getComentario(){
        return $this->comentario;
    }

    public function getEstado(){
        return $this->estado;
    }

    public function getEstadoNombre(){
        if ($this->estado == 'P') {
            return 'Pendiente';
        } elseif ($this->estado == 'F') {
            return 'Facturado';
        } elseif ($this->estado == 'C') {
            return 'Cancelado';
        } elseif ($this->estado == 'V') {
            return 'Vencido';
        }
    }

    public function setEstado($estado){
        $this->estado = $estado;
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal');
    }

    public function moneda()
    {
        return $this->belongsTo('App\Moneda');
    }

    public function ordenPagoCheques(){
        return $this->hasMany('App\OrdenPagoCheques', 'orden_pago_id');
    }

    public function ordenPagoFacturas(){
        return $this->hasMany('App\ordenPagoFacturas', 'orden_pago_id');
    }
}
