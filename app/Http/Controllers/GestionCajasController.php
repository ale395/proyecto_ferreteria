<?php

namespace App\Http\Controllers;

use App\Caja;
use Validator;
use App\CobranzaCab;
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
    	$sucursal_actual = Auth::user()->empleado->sucursalDefault;
        $cajas_sucursal_actual = Caja::where('sucursal_id', $sucursal_actual->getId())->where('activo', true)->get();
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

        $otra_habilitacion = HabilitacionCaja::where('caja_id', $request['caja_id'])
            ->whereNull('fecha_hora_cierre')->first();

        if (!empty($otra_habilitacion)) {
            return redirect()->back()->withErrors('La caja seleccionada se encuentra habilitada por el usuario '.$otra_habilitacion->usuario->getName().'! No podrá habilitar la caja hasta que la misma sea cerrada.');
        }

        $otra_habilitacion_usuario = HabilitacionCaja::where('user_id', $request['user_id'])
            ->whereNull('fecha_hora_cierre')
            ->whereIn('caja_id', $cajas_sucursal_actual->pluck('id')->toArray())->first();

        if (!empty($otra_habilitacion_usuario)) {
            return redirect()->back()->withErrors('El usuario '.$otra_habilitacion_usuario->usuario->getName().' tiene otra caja abierta en la misma sucursal! Cierre la caja '.$otra_habilitacion_usuario->caja->getNombre().' para poder habilitar otra.');
        }

        $habilitacion->setUsuarioId($request['user_id']);
        $habilitacion->setCajaId($request['caja_id']);
        $habilitacion->setSaldoInicial(str_replace('.', '', $request['saldo_inicial']));
        $habilitacion->save();

        return redirect()->back()->with('status', 'Caja habilitada correctamente! N° de habilitacion: '.$habilitacion->getId());
    }

    public function cerrarCajaView(){
        $sucursal_actual = Auth::user()->empleado->sucursalDefault;
    	$cajas_sucursal_actual = Caja::where('sucursal_id', $sucursal_actual->getId())->where('activo', true)->get();

        $habilitacion = HabilitacionCaja::where('user_id', Auth::user()->getId())
            ->whereNull('fecha_hora_cierre')
            ->whereIn('caja_id', $cajas_sucursal_actual->pluck('id')->toArray())->first();

        if (count($habilitacion) == 0) {
            return redirect('/gestionCajas/habilitarCaja')->with('status', 'No tiene habilitación de caja pendiente de cierre!');
        }

        $cobranzas = CobranzaCab::where('habilitacion_id', $habilitacion->id)
            ->where('estado', 'R')->get();
        $saldo_final = $habilitacion->getSaldoInicialNumber() + $cobranzas->sum('monto_total') - $cobranzas->sum('vuelto');

        return view('gestionCobranza.cerrarCaja', compact('habilitacion', 'saldo_final'));
    }

    public function cerrarCaja(Request $request){
        $habilitacion = HabilitacionCaja::findOrFail($request['id']);
        $fecha_hora_cierre = date('Y-m-d H:i:s');
        $habilitacion->setSaldoFinal(str_replace('.', '', $request['saldo_final']));
        $habilitacion->setFechaHoraCierre($fecha_hora_cierre);
        $habilitacion->update();

        return redirect('/gestionCajas/habilitarCaja')->with('status', 'Caja cerrada correctamente! N° de habilitacion: '.$habilitacion->getId());
    }
}