<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $table = 'bancos';

    protected $fillable = ['codigo', 'nombre', 'activo'];

    public function getId(){
        return $this->id;
    }
    
    public function getNombreSelect(){
            return '('.$this->codigo.') '.$this->nombre;
    }

    public function getNombre(){
    	return $this->nombre;
    }
}
