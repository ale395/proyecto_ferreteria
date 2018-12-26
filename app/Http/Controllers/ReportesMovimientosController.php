<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use App\Articulo;
use App\MovimientoArticulo;
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

        $sucursal_id = $request['sucursal_id'];     
        $sucursal = Sucursal::findOrFail($sucursal_id);
        $movimientos = DB::table('movimientos_articulos')
        ->join('articulos', 'movimientos_articulos.articulo_id', '=', 'articulos.id')

            ->where('movimientos_articulos.sucursal_id', $sucursal_id);
           // ->where('movimientos_articulos.articulo_id', $articulo_id);

        if ($request->has('sucursal_id')) {
            $movimientos = $movimientos->whereIn('movimientos_articulos.sucursal_id', $request['sucursal_id']);
            if (count($request['sucursal_id']) > 1) {
                $sucursal = 'Multiple seleccion';
            } else {
                $sucursal = Sucursal::findOrFail($request['sucursal_id'][0]);
                $sucursal = $sucursal->getNombre();
            }
        } else {
            $sucursal = 'Todas';
        }
        
       
           $movimientos = $movimientos->get();
    	$pdf = PDF::loadView('reportesStock.movimientosReporte', compact('movimientos','fecha_inicial', 'fecha_final','articulo','sucursal'))->setPaper('a4', 'landscape');

        return $pdf->stream('ReporteDeMovimientos.pdf',array('Attachment'=>0));
    }
}
