<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConceptoAjuste extends Model
{
    protected $table = 'conceptos_ajustes';

    protected $fillable = ['num_concepto', 'descripcion','signo'];
    public function getId(){
    	return $this->id;
    }

    public function getDescripcion(){
    	return $this->descripcion;
    }
    public function getSignoOperacion(){
    	return $this->signo;
    }
}
