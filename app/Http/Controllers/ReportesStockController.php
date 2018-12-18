<?php

namespace App\Http\Controllers;

use Validator;
use App\Articulo;
use App\ExistenciaArticulo;
use App\Sucursal;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class ReportesStockController extends Controller
{
    public function viewArticuloExistencia(){
    	$fecha_actual = date("d/m/Y");;
    	return view('reportesStock.existenciaArticuloFiltros', compact('fecha_actual'));
    }

    public function verArticuloExistencia(Request $request){
    	
    	$rules = [
            'fecha_final' => 'required',
            'sucursal_id' => 'required',

        ];

        $mensajes = [
            'sucursal_id.required' => 'Debe seleccionar una sucursal para ejecutar el reporte!',
            'fecha_final.required' => 'Debe especificar la fecha final para ejecutar el reporte!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes)->validate();

        $sucursal_id = $request['sucursal_id'];
        $fecha_final = $request['fecha_final'];

        $sucursal = Sucursal::findOrFail($sucursal_id);

        $registros = DB::table('existencia_articulos')
        ->join('articulos', 'existencia_articulos.articulo_id', '=', 'articulos.id')
        	->where('existencia_articulos.sucursal_id', $sucursal_id);

        $registros = $registros->get();
        $pdf = PDF::loadView('reportesStock.existenciaArticuloReporte', compact('registros','articulos', 'sucursal', 'fecha_final'));

        return $pdf->stream('ArticuloExistencia.pdf',array('Attachment'=>0));
    }
}
