<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    
    protected $casts = [
    	'created_at' => 'Y-m-d H:i:s',
    	'updated_at' => 'Y-m-d H:i:s',
    	'fecha_cotizacion' => 'date:d/m/Y',
	];
    protected $fillable = [
        'descripcion', 'moneda_id','fecha_cotizacion','valor_compra','valor_venta',
    ];

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }

    public function getValorVenta(){
        return number_format($this->valor_venta, 0, ',', '.');
    }

    public function getValorCompra(){
        return number_format($this->valor_compra, 0, ',', '.');
    }
}
