<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListaPrecioCabecera extends Model
{
    protected $table = 'lista_precios_cabecera';

    protected $fillable = ['lista_precio', 'descripcion', 'moneda_id'];
}
