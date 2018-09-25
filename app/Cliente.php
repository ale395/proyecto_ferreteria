<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'tipo_persona', 'nombre', 'apellido', 'razon_social', 'ruc', 'nro_cedula', 'telefono_celular', 'telefono_linea_baja', 'direccion', 'correo_electronico', 'zona_id','tipo_cliente_id', 'activo',
    ];

    public function getTipoPersona(){
        return $this->tipo_persona;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getApellido(){
        return $this->apellido;
    }

    public function getRazonSocial(){
        return $this->razon_social;
    }

    public function getRuc(){
        return $this->ruc;
    }

    public function getNroCedula(){
        return $this->nro_cedula;
    }

    public function getTelefonoCelular(){
        return $this->telefono_celular;
    }

    public function getTelefonoLineaBaja(){
        return $this->telefono_linea_baja;
    }

    public function getDireccion(){
        return $this->direccion;
    }

    public function getCorreoElectronico(){
        return $this->correo_electronico;
    }

    public function getActivo(){
        return $this->activo;
    }

    public function zona()
    {
        return $this->belongsTo('App\Zona');
    }

    public function tipoCliente()
    {
        return $this->belongsTo('App\CategoriaCliente');
    }

}
