<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';

    protected $fillable = [
        'razon_social', 'ruc', 'direccion', 'correo_electronico', 'sitio_web', 'eslogan', 'telefono', 'rubro', 'codigo_establecimiento', 'moneda_nacional_id',
    ];

    public function setRazonSocial($razon_social){
        $this->razon_social = $razon_social;
    }

    public function setRuc($ruc){
        $this->ruc = $ruc;
    }

    public function setDireccion($direccion){
        $this->direccion = $direccion;
    }

    public function setCorreoElectronico($correo_electronico){
        $this->correo_electronico = $correo_electronico;
    }

    public function setSitioWeb($sitio_web){
        $this->sitio_web = $sitio_web;
    }

    public function setTelefono($telefono){
        $this->telefono = $telefono;
    }

    public function setRubro($rubro){
        $this->rubro = $rubro;
    }

    public function setMonedaNacionalId($id){
        $this->moneda_nacional_id = $id;
    }

    public function setCodigoEstablecimiento($codigo){
        $this->codigo_establecimiento = $codigo;
    }

    public function getCodigoEstablecimiento(){
    	return $this->codigo_establecimiento;
    }

    public function moneda()
    {
        return $this->belongsTo('App\Moneda', 'moneda_nacional_id');
    }
}
