<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'tipo_persona', 'nombre', 'apellido', 'razon_social', 'ruc', 'nro_cedula', 'telefono_celular', 'telefono_linea_baja', 'direccion', 'correo_electronico', 'zona_id','tipo_cliente_id', 'activo',
    ];

    public function getId(){
        return $this->id;
    }

    public function getTipoPersona(){
        return $this->tipo_persona;
    }

    public function getTipoPersonaIndex(){
        if ($this->tipo_persona == 'F') {
            return 'Física';
        } elseif ($this->tipo_persona == 'J') {
            return 'Jurídica';
        }
    }

    public function setTipoPersona($tipo_persona){
        $this->tipo_persona = $tipo_persona;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getNombreIndex(){
        if ($this->tipo_persona == 'F') {
            return $this->nombre . ', '.$this->apellido;
        } elseif ($this->tipo_persona == 'J') {
            return $this->razon_social;
        }
    }

    public function getNombreSelect(){
        if ($this->tipo_persona == 'F') {
            return $this->nro_cedula.' - '.$this->nombre . ', '.$this->apellido;
        } elseif ($this->tipo_persona == 'J') {
            return $this->ruc.' - '.$this->razon_social;
        }
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

    public function getRazonSocial(){
        return $this->razon_social;
    }

    public function setRazonSocial($razon_social){
        $this->razon_social = $razon_social;
    }

    public function getNroDocumentoIndex(){
        if (is_null($this->ruc)) {
            return number_format($this->nro_cedula, 0, ',', '.');
        } else {
            return $this->ruc;
        }
    }

    public function getTipoDocumentoIndex(){
        if (is_null($this->ruc)) {
            return 'Cédula';
        } else {
            return 'RUC';
        }
    }

    public function getRuc(){
        return $this->ruc;
    }

    public function setRuc($ruc){
        $this->ruc = $ruc;
    }

    public function getNroCedula(){
        return number_format($this->nro_cedula, 0, ',', '.');
    }

    public function setNroCedula($nro_cedula){
        $numero_entero = (integer) str_replace('.', '', $nro_cedula);
        if ($numero_entero != 0) {
            $this->nro_cedula = $numero_entero;
        }
    }

    public function getTelefonoCelular(){
        return $this->telefono_celular;
    }

    public function setTelefonoCelular($telefono_celular){
        $numero_entero = (integer)str_replace(" ","",str_replace(")","",str_replace("(","",str_replace("-","",$telefono_celular))));
        if ($numero_entero != 0) {
            $this->telefono_celular = $numero_entero;
        }
    }

    public function getTelefonoLineaBaja(){
        return $this->telefono_linea_baja;
    }

    public function setTelefonoLineaBaja($telefono_linea_baja){
        $numero_entero = (integer)str_replace(" ","",str_replace(")","",str_replace("(","",str_replace("-","",$telefono_linea_baja))));
        if ($numero_entero != 0) {
            $this->telefono_linea_baja = $numero_entero;
        }
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

    public function getActivo(){
        return $this->activo;
    }

    public function setActivo($activo){
        $this->activo = $activo;
    }

    public function getMontoSaldo(){
        return $this->monto_saldo;
    }

    public function setMontoSaldo($monto_saldo){
        $this->monto_saldo = $monto_saldo;
    }

    public function setZonaId($zona_id){
        $this->zona_id = $zona_id;
    }

    public function setTipoClienteId($tipo_cliente_id){
        $this->tipo_cliente_id = $tipo_cliente_id;
    }

    public function zona()
    {
        return $this->belongsTo('App\Zona');
    }

    public function tipoCliente()
    {
        return $this->belongsTo('App\ClasificacionCliente');
    }

}
