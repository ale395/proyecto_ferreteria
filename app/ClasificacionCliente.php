<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClasificacionCliente extends Model
{
    protected $table = 'tipos_clientes';

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

