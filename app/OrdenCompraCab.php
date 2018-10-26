<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenCompraCab extends Model
{
    protected $table = 'orden_compras_cab';
    
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

    public function cliente()
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
}
