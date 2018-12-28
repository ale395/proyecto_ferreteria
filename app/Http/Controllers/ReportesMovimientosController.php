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
        $sucursal = null;     
        $articulo  = null;   
        //dd($articulo_id);  
       // $sucursal = Sucursal::findOrFail($sucursal_id);
        $movimientos = DB::table('movimientos_articulos')
        ->join('articulos', 'movimientos_articulos.articulo_id', '=', 'articulos.id')
        ->join('sucursales', 'movimientos_articulos.sucursal_id', '=', 'sucursales.id')
        ->select('movimientos_articulos.fecha_movimiento', 'movimientos_articulos.movimiento_id','articulos.descripcion',
        DB::raw("case when tipo_movimiento = 'C' THEN 'Compra' when tipo_movimiento = 'A' THEN 'Ajuste'  when tipo_movimiento = 'F' THEN 'Factura'  when tipo_movimiento = 'Z' THEN 'Nota Débito' ELSE 'Inventario' END AS tipo_movimiento"),
        DB::raw("case when tipo_movimiento = 'C' THEN cantidad when tipo_movimiento = 'A' THEN cantidad  when tipo_movimiento = 'F' THEN cantidad*-1  when tipo_movimiento = 'Z' THEN cantidad ELSE cantidad END AS cantidad"))
    // ->where('movimientos_articulos.sucursal_id', $sucursal_id)
      //  ->where('movimientos_articulos.articulo_id', $articulo_id);
      ->where('movimientos_articulos.fecha_movimiento', '>=', $fecha_inicial)
      ->where('movimientos_articulos.fecha_movimiento', '<=', $fecha_final);

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
        
        if ($request->has('articulo_id')) {
            $movimientos = $movimientos->whereIn('movimientos_articulos.articulo_id', $request['articulo_id']);
            if (count($request['articulo_id']) > 1) {
                $articulo = 'Multiple seleccion';
            } else {
                $articulo = Articulo::findOrFail($request['articulo_id'][0]);
                $articulo = $articulo->getDescripcion();
            }
        } else {
            $articulo = 'Todos';
        }
           $movimientos = $movimientos->get();
           $saldo=0;
           foreach ($movimientos as $movimiento) {
             $saldo = $saldo + $movimiento->cantidad;
               }
    	$pdf = PDF::loadView('reportesStock.movimientosReporte', compact('movimientos','fecha_inicial', 'fecha_final','articulo','sucursal', 'saldo'))->setPaper('a4', 'landscape');

        return $pdf->stream('ReporteDeMovimientos.pdf',array('Attachment'=>0));
    }
}
