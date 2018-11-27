<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = 
    ['codigo', 'nombre','razon_social','ruc','nro_documento',
    'telefono','direccion','correo','tipo_vendedor_id','activo'];

    public function getActivo(){
        return $this->activo;
    }

    public function getId(){
        return $this->id;
    }

    public function setActivo($activo){
        $this->activo = $activo;
    }
    
    public function getNombreIndex(){
            return $this->apellido. ', '.$this->nombre;
    }

    public function tipoProveedor()
    {
        return $this->belongsTo('App\TipoProveedor');
    }
}
