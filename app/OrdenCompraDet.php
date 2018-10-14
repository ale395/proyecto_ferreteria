<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenCompraDet extends Model
{
    protected $table = 'pedidos_ventas_det';

    protected $fillable = [
        'orden_compra_cab_id', 
        'articulo_id', 
        'cantidad', 
        'costo_unitario', 
        'costo_promedio', 
        'sub_total',
        'porcentaje_iva',
        'total_exenta',
        'total_gravada'
    ];

    public function articulo()
    {
        return $this->belongsTo('App\Articulo');
    }

    public function pedidoCabecera()
    {
        return $this->belongsTo('App\OrdenCompraCab');
    }
}
