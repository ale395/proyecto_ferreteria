<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HabilitacionCaja extends Model
{
    protected $table = 'habilitacion_caja';

    protected $fillable = [
    'user_id', 'caja_id','fecha_hora_habilitacion', 'fecha_hora_cierre', 'saldo_inicial', 'saldo_final',
    ];

    public function getId()
    {
    	return $this->id;
    }

    public function setUsuarioId($user_id){
        $this->user_id = $user_id;
    }

    public function setCajaId($caja_id){
        $this->caja_id = $caja_id;
    }

    public function getFechaHoraHabilitacion(){
    	return $this->fecha_hora_habilitacion;
    }

    public function getFechaHoraCierre(){
    	return $this->fecha_hora_cierre();
    }

    public function getSaldoInicialNumber(){
        return $this->saldo_inicial;
    }

    public function setSaldoInicial($saldo_inicial){
        $this->saldo_inicial = $saldo_inicial;
    }

    public function getSaldoFinalNumber(){
    	return $this->saldo_final;
    }

    public function caja()
    {
        return $this->belongsTo('App\Caja');
    }

    public function usuario()
    {
        return $this->belongsTo('App\User');
    }
}
