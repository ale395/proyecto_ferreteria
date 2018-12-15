<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class ReportesVentasController extends Controller
{
    public function viewReporteVentas(){
    	$fecha_actual = date("d/m/Y");;
    	return view('reportesVentas.ventasFiltros', compact('fecha_actual'));
    }

    public function verReporteVentas(Request $request){
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

        $facturas = DB::table('facturas_ventas_cab')
            ->join('facturas_ventas_det', 'facturas_ventas_cab.id', '=', 'facturas_ventas_det.factura_cab_id')
            ->join('clientes', 'clientes.id', '=', 'facturas_ventas_cab.cliente_id')
            ->join('sucursales', 'sucursales.id', '=', 'facturas_ventas_cab.sucursal_id')
            ->select(DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
                DB::raw("facturas_ventas_cab.serie||' '||lpad(CAST(facturas_ventas_cab.nro_factura AS CHAR), 7, '0') as nro_comp"),
                DB::raw("CASE WHEN clientes.tipo_persona = 'F' THEN clientes.nombre||', '||clientes.apellido ELSE clientes.razon_social END AS cliente"),
                DB::raw('sucursales.nombre AS sucursal'),
                DB::raw("TO_CHAR(SUM(facturas_ventas_det.monto_exenta), '999G999G999') as total_exenta"),
                DB::raw("TO_CHAR(SUM(facturas_ventas_det.monto_gravada), '999G999G999') as total_gravada"), 
                DB::raw("TO_CHAR(SUM(facturas_ventas_det.monto_iva), '999G999G999') as total_iva"),
                DB::raw("TO_CHAR(SUM(facturas_ventas_det.monto_total), '999G999G999') as total_comprobante"))
            ->where('facturas_ventas_cab.estado', '!=', 'A')
            ->where('facturas_ventas_cab.fecha_emision', '>=', $fecha_inicial)
            ->where('facturas_ventas_cab.fecha_emision', '<=', $fecha_final)
            ->groupBy('facturas_ventas_cab.fecha_emision', 'facturas_ventas_cab.serie', 'facturas_ventas_cab.nro_factura', 'sucursales.nombre', 'clientes.tipo_persona', 'clientes.nombre', 'clientes.apellido', 'clientes.razon_social');

    	$sucursales = $facturas->distinct('sucursal')->get();
    	$sucursales = $sucursales->groupBy('sucursal');
    	$facturas = $facturas->get();

    	$pdf = PDF::loadView('reportesVentas.ventasReporte', compact('facturas', 'fecha_inicial', 'fecha_final', 'sucursales'))->setPaper('a4', 'landscape');

        return $pdf->stream('ReporteDeVentas.pdf',array('Attachment'=>0));
    }
}
