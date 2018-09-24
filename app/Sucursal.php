<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';

    protected $fillable = ['codigo', 'nombre', 'direccion', 'codigo_punto_expedicion','activo'];

    public function getId(){
    	return $this->id;
    }

    public function getCodigo(){
    	return $this->codigo;
    }

    public function getNombre(){
    	return $this->nombre;
    }

    public function getDireccion(){
    	return $this->direccion;
    }

    public function getCodigoPuntoExpedicion(){
    	return $this->codigo_punto_expedicion;
    }

    public function getActivo(){
    	return $this->activo;
    }
}
