<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    protected $table = 'monedas';

    protected $fillable = [
    'codigo', 'descripcion','simbolo', 'maneja_decimal',
    ];

    public function getManejaDecimal()
    {
    	return $this->maneja_decimal;
    }

    public function getId(){
    	return $this->id;
    }

    public function getDescripcion(){
        return $this->descripcion;
    }
}
