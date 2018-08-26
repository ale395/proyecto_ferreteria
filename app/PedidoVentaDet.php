<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoVentaDet extends Model
{
    protected $table = 'pedidos_ventas_det';

    protected $fillable = [
        'pedido_cab_id', 'articulo_id', 'cantidad', 'precio_unitario', 'monto_total',
    ];

    public function articulo()
    {
        return $this->belongsTo('App\Articulo');
    }

    public function pedidoCabecera()
    {
        return $this->belongsTo('App\PedidoVentaCab');
    }

}
