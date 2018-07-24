<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ListaPrecioDetalle extends Model
{
    protected $table = 'lista_precios_detalle';

    protected $fillable = ['lista_precio_id', 'articulo_id', 'fecha_vigencia', 'precio'];

    public function articulo()
    {
        return $this->belongsTo('App\Articulo');
    }

    public function listaPrecioCabecera()
    {
        return $this->belongsTo('App\ListaPrecioCabecera');
    }

    public function formatoFecha() {
        return (new Carbon($this->attributes['fecha_vigencia']))->format('d/m/Y');
    }
}
