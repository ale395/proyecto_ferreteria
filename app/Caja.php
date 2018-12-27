<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'cajas';

    protected $fillable = [
    	'nombre', 'sucursal_id', 'activo',
    ];

    public function getId(){
    	return $this->id;
    }

    public function getNombre(){
    	return $this->nombre;
    }

    public function setNombre($nombre){
    	$this->nombre = $nombre;
    }

    public function getActivo(){
    	return $this->activo;
    }

    public function setActivo($activo){
    	$this->activo = $activo;
    }

    public function setSucursalId($sucursal_id){
        $this->sucursal_id = $sucursal_id;
    }
}
