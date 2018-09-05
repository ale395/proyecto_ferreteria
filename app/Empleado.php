<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $codigo_pais = '+595';

    protected $fillable = [
        'nro_cedula', 'nombre', 'apellido', 'direccion', 'zona_id', 'telefono_celular', 'telefono_linea_baja', 'correo_electronico', 'fecha_nacimiento', 'activo',
    ];

    public function getNroCedula(){
    	return number_format($this->nro_cedula, 0, ',', '.');
    }

    public function setNroCedula($nro_cedula){
    	$this->nro_decula = (integer) str_replace('.', '', $nro_cedula);
    }

    public function getTelefonoCelular(){
		$telefono_celular = preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{3}).*~', '$1) $2-$3', $this->telefono_celular);
		return '(' . $this->codigo_pais . $telefono_celular;
    }

    public function setTelefonoCelular($telefono_celular){
    	//
    }

    public function zona()
    {
        return $this->belongsTo('App\Zona');
    }

    public function tiposEmpleados()
    {
    	return $this->belongsToMany('App\TipoEmpleado')->withTimestamps();
    }
    
}
