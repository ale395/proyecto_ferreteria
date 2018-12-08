<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivoAnulacion extends Model
{
    protected $table = 'motivos_anulaciones';

    protected $fillable = [
    	'nombre',
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
}
