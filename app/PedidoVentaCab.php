<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoVentaCab extends Model
{
    protected $table = 'pedidos_ventas_cab';

    protected $fillable = [
        'nro_pedido', 'cliente_id', 'sucursal_id', 'moneda_id', 'valor_cambio', 'fecha_emision', 'monto_total', 'estado',
    ];

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
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
