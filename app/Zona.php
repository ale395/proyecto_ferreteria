<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $table = 'zonas';

    protected $fillable = ['codigo', 'nombre'];

    public function getId(){
    	return $this->id;
    }

    public function getCodigo(){
    	return $this->codigo;
    }

    public function getNombre(){
    	return $this->nombre;
    }
}
