<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

class ReportesCuentasPorCobrarController extends Controller
{
    public function viewExtractoCliente(){
    	$fecha_actual = date("d/m/Y");;
    	return view('reportesCuentasPorCobrar.extractoClienteFiltros', compact('fecha_actual'));
    }

    public function verExtractoCliente(Request $request){
    	
    	$rules = [
            'cliente_id' => 'required',
            'fecha_final' => 'required',
        ];

        $mensajes = [
            'cliente_id.required' => 'Debe seleccionar un cliente para ejecutar el reporte!',
            'fecha_final.required' => 'Debe especificar la fecha final para ejecutar el reporte!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes)->validate();
        /*ARMAR EL REPORTE*/
    }
}
