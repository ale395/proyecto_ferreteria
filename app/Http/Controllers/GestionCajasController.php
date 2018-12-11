<?php

namespace App\Http\Controllers;

use App\Caja;
use Validator;
use App\HabilitacionCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class GestionCajasController extends Controller
{
    public function habilitarCajaView(){
    	$cajas = Caja::where('sucursal_id', Auth::user()->empleado->sucursalDefault->getId())->where('activo', true)->get();
    	return view('gestionCobranza.habilitarCaja', compact('cajas'));
    }

    public function habilitarCaja(Request $request){
    	//FALTA AGREGAR QUE VERIFIQUE SI LA CAJA YA NO ESTE ABIERTA
    	$habilitacion = new HabilitacionCaja;

    	$rules = [
            'caja_id' => 'required',
            'saldo_inicial' => 'required',
        ];

        $mensajes = [
            'caja_id.required' => 'Debe seleccionar una caja para la habilitación!',
            'saldo_inicial.required' => 'Debe ingresar el monto con el que abrirá la caja!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes)->validate();

        $habilitacion->setUsuarioId($request['user_id']);
        $habilitacion->setCajaId($request['caja_id']);
        $habilitacion->setSaldoInicial(str_replace('.', '', $request['saldo_inicial']));
        $habilitacion->save();

        return redirect()->back()->with('status', 'Caja habilitada correctamente! N° de habilitacion: '.$habilitacion->getId());
    }

    public function cerrarCajaView(){
    	//
    }
}