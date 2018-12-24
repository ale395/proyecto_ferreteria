<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenPagoCheques extends Model
{
    protected $table = 'orden_pago_cheques';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'orden_pago_id', 
        'banco_id', 
        'moneda_id', 
        'valor_cambio', 
        'nro_cuenta',
        'librador', 
        'fecha_emision', 
        'fecha_vencimiento', 
        'importe'
    ];

    public function getId(){
        return $this->id;
    }

    public function setOrdenPagoId($orden_pago_id){
        $this->orden_pago_id = $orden_pago_id;
    }

    public function setBancoId($banco_id){
        $this->banco_id = $banco_id;
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

    public function getNroCuenta(){
        return $this->nro_cuenta;
    }

    public function setNroCuenta($nro_cuenta){
        $this->estado = $nro_cuenta;
    }

    public function getLibrador(){
        return $this->librador;
    }

    public function setLibrador($librador){
        $this->estado = $librador;
    }

    public function setFechaEmision($fecha_emision){
        $this->fecha_emision = $fecha_emision;
    }

    public function getFechaEmision(){
        return date("d-m-Y", strtotime($this->fecha_vencimiento));
    }

    //fecha_vigencia_timbrado
    public function setFechaVencimiento($fecha_vencimiento){
        $this->fecha_vencimiento = $fecha_vencimiento;
    }

    public function getFechaVencimiento(){
        return date("d-m-Y", strtotime($this->fecha_vencimiento));
    }

    public function setImporte($importe){
        $this->importe = $importe;
    }

    public function getImporte(){
        return number_format($this->importe, 0, ',', '.');
    }


    public function moneda()
    {
        return $this->belongsTo('App\Moneda');
    }

    public function usuario()
    {
        return $this->belongsTo('App\User');
    }

    public function banco()
    {
        return $this->belongsTo('App\Bancos');
    }

    public function ordenpago(){
        return $this->hasOne('App\OrdenPago', 'orden_pago_id');
    }
}
