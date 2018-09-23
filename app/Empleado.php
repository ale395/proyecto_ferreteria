<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $codigo_pais = '0';

    protected $fillable = [
        'nro_cedula', 'nombre', 'apellido', 'direccion', 'zona_id', 'telefono_celular', 'telefono_linea_baja', 'correo_electronico', 'fecha_nacimiento', 'activo', 'avatar',
    ];

    public function getNroCedula(){
    	return number_format($this->nro_cedula, 0, ',', '.');
    }

    public function setNroCedula($nro_cedula){
    	$this->nro_cedula = (integer) str_replace('.', '', $nro_cedula);
    }

    public function getTelefonoCelular(){
		$telefono_celular = preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{3}).*~', '$1) $2-$3', $this->telefono_celular);
		return '(' . $this->codigo_pais . $telefono_celular;
    }

    public function getTelefonoCelularNumber(){
    	return $this->telefono_celular;
    }

    public function setTelefonoCelular($telefono_celular){
    	$this->telefono_celular = (integer)str_replace(" ","",str_replace(")","",str_replace("(","",str_replace("-","",$telefono_celular))));
    }

    public function getNombre(){
    	return $this->nombre;
    }

    public function setNombre($nombre){
    	$this->nombre = $nombre;
    }

    public function getApellido(){
    	return $this->apellido;
    }

    public function setApellido($apellido){
    	$this->apellido = $apellido;
    }

    public function getDireccion(){
    	return $this->direccion;
    }

    public function setDireccion($direccion){
    	$this->direccion = $direccion;
    }

    public function getCorreoElectronico(){
    	return $this->correo_electronico;
    }

    public function setCorreoElectronico($correo_electronico){
    	$this->correo_electronico = $correo_electronico;
    }

    public function getFechaNacimiento(){
    	return date("d-m-Y", strtotime($this->fecha_nacimiento));
    }

    public function setFechaNacimiento($fecha_nacimiento){
    	$this->fecha_nacimiento = $fecha_nacimiento;
    }

    public function zona()
    {
        return $this->belongsTo('App\Zona');
    }

    public function tiposEmpleados()
    {
    	return $this->belongsToMany('App\TipoEmpleado')->withTimestamps();
    }

    public function sucursales()
    {
        return $this->belongsToMany('App\Sucursal')->withTimestamps();
    }
    
}
