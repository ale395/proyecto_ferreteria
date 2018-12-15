<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

class ReportesVentasController extends Controller
{
    public function viewReporteVentas(){
    	$fecha_actual = date("d/m/Y");;
    	return view('reportesVentas.ventasFiltros', compact('fecha_actual'));
    }

    public function verReporteVentas(Request $request){
    	$rules = [
            'fecha_inicial' => 'required|date_format:d/m/Y',
            'fecha_final' => 'required|date_format:d/m/Y|after:fecha_inicial',
        ];

        $mensajes = [
            'fecha_inicial.required' => 'El campo fecha inicial es obligatorio!',
            'fecha_final.required' => 'El campo fecha final es obligatorio!',
            'fecha_final.after' => 'La fecha final no puede ser menor a la fecha inicial!',
        ];

        Validator::make($request->all(), $rules, $mensajes)->validate();

    	return $request;
    }
}
