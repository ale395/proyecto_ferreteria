<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use App\Articulos;
use App\Sucursal;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class ReportesMovimientosController extends Controller
{
    public function viewReporteMovimientos(){
    	$fecha_actual = date("d/m/Y");;
    	return view('reportesStock.movimientosFiltros', compact('fecha_actual'));
    }

    public function verReporteMovimientos(Request $request){
    	$rules = [
            'fecha_inicial' => 'required|date_format:d/m/Y',
            'fecha_final' => 'required|date_format:d/m/Y|after_or_equal:fecha_inicial',
        ];

        $mensajes = [
            'fecha_inicial.required' => 'El campo fecha inicial es obligatorio!',
            'fecha_final.required' => 'El campo fecha final es obligatorio!',
            'fecha_final.after_or_equal' => 'La fecha final no puede ser menor a la fecha inicial!',
        ];

        Validator::make($request->all(), $rules, $mensajes)->validate();
        
        $fecha_inicial = $request['fecha_inicial'];
        $fecha_final = $request['fecha_final'];
        $articulo = null;
        $sucursal = null;

    	$pdf = PDF::loadView('reportesStock.movimientosReporte', compact('fecha_inicial', 'fecha_final',  'articulo','sucursal'))->setPaper('a4', 'landscape');

        return $pdf->stream('ReporteDeMovimientos.pdf',array('Attachment'=>0));
    }
}
