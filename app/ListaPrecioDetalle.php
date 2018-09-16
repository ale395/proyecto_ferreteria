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

    public function moneda()
    {
        return $this->hasManyThrough('App\Moneda', 'App\ListaPrecioCabecera');
    }

    public function getFechaVigencia() {
        return date("d/m/Y", strtotime($this->fecha_vigencia));;
    }

    public function setFechaVigencia($fecha_vigencia)
    {
        $this->fecha_vigencia = $fecha_vigencia;
    }

    public function getPrecio()
    {
        //Si la moneda de la lista de precios maneja decimales devuelve el precio con 2 posiciones decimales, caso contrario lo devuelve como número entero
        if (ListaPrecioCabecera::findOrFail($this->lista_precio_id)->moneda->getManejaDecimal()) {
            return number_format($this->precio, 2, ',', '.');
        } else {
            return number_format($this->precio, 0, ',', '.');
        }
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    public function setArticuloId($articulo_id)
    {
        $this->articulo_id = $articulo_id;
    }

    public function setListaPrecioId($lista_precio_id)
    {
        $this->lista_precio_id = $lista_precio_id;
    }
}
