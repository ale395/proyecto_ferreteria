<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $table = 'formas_pagos';
    
    protected $fillable = 
    ['codigo', 'descripcion', 'control_valor'];

    public function getId(){
        return $this->id;
    }
    
    public function getNombreSelect(){
        if (is_null($this->codigo) || $this->codigo == 0) {
            return '('.$this->codigo.') '.$this->descripcion;
        } else {
            return '('.$this->codigo.') '.$this->descripcion;
        }
    }


}
