<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenPagoFacturas extends Model
{
    protected $table = 'orden_pago_facturas';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'orden_pago_id', 
        'compras_id', 
        'importe_afectado'
    ];

    public function setOrdenPagoId($orden_pago_id){
        $this->orden_pago_id = $orden_pago_id;
    }

    public function setCompraId($compras_id){
        $this->compras_id = $compras_id;
    }

    public function setImporte($importe_afectado){
        $this->importe_afectado = $importe_afectado;
    }

    public function getImporte(){
        return number_format($this->importe_afectado, 0, ',', '.');
    }

    public function compra()
    {
        return $this->belongsTo('App\ComprasCab');
    }

    public function ordenpago(){
        return $this->hasOne('App\OrdenPago', 'orden_pago_id');
    }
}
