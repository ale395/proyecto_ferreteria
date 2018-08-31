<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    
    protected $fillable = [
        'descripcion', 'moneda_id','fecha_cotizacion','valor_compra','valor_venta',
    ];

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }
}
