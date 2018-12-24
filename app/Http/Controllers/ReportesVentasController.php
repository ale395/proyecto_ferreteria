<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use App\Cliente;
use App\Sucursal;
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
        $cliente = null;
        $sucursal = null;
        $vendedor = null;
        $incluye_anulados = $request['anulados'];

        $facturas = DB::table('facturas_ventas_cab')
            ->join('facturas_ventas_det', 'facturas_ventas_cab.id', '=', 'facturas_ventas_det.factura_cab_id')
            ->join('clientes', 'clientes.id', '=', 'facturas_ventas_cab.cliente_id')
            ->join('sucursales', 'sucursales.id', '=', 'facturas_ventas_cab.sucursal_id')
            ->select(DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
                DB::raw("CASE WHEN facturas_ventas_cab.estado = 'A' THEN 'Factura' ELSE 'Factura' END as tipo_comp"),
                DB::raw("CASE WHEN facturas_ventas_cab.estado = 'A' THEN 'Anulado' WHEN facturas_ventas_cab.estado = 'P' THEN 'Pendiente' END as estado"),
                DB::raw("facturas_ventas_cab.nume_serie||' '||lpad(CAST(facturas_ventas_cab.nro_factura AS CHAR), 7, '0') as nro_comp"),
                DB::raw("CASE WHEN clientes.tipo_persona = 'F' THEN clientes.nombre||', '||clientes.apellido ELSE clientes.razon_social END AS cliente"),
                DB::raw('sucursales.nombre AS sucursal'),
                DB::raw("SUM(CASE WHEN facturas_ventas_cab.estado = 'A' THEN 0 ELSE facturas_ventas_det.monto_descuento END) as total_descuento"),
                DB::raw("SUM(CASE WHEN facturas_ventas_cab.estado = 'A' THEN 0 ELSE facturas_ventas_det.monto_exenta END) as total_exenta"),
                DB::raw("SUM(CASE WHEN facturas_ventas_cab.estado = 'A' THEN 0 ELSE facturas_ventas_det.monto_gravada END) as total_gravada"), 
                DB::raw("SUM(CASE WHEN facturas_ventas_cab.estado = 'A' THEN 0 ELSE facturas_ventas_det.monto_iva END) as total_iva"),
                DB::raw("SUM(CASE WHEN facturas_ventas_cab.estado = 'A' THEN 0 ELSE facturas_ventas_det.monto_total END) as total_comprobante"))
            ->where('facturas_ventas_cab.fecha_emision', '>=', $fecha_inicial)
            ->where('facturas_ventas_cab.fecha_emision', '<=', $fecha_final);

        $notas_credito = DB::table('nota_credito_ventas_cab')
            ->join('nota_credito_ventas_det', 'nota_credito_ventas_cab.id', '=', 'nota_credito_ventas_det.nota_credito_cab_id')
            ->join('clientes', 'clientes.id', '=', 'nota_credito_ventas_cab.cliente_id')
            ->join('sucursales', 'sucursales.id', '=', 'nota_credito_ventas_cab.sucursal_id')
            ->select(DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
                DB::raw("CASE WHEN nota_credito_ventas_cab.estado = 'A' THEN 'Nota de Credito' ELSE 'Nota de Credito' END as tipo_comp"),
                DB::raw("CASE WHEN nota_credito_ventas_cab.estado = 'A' THEN 'Anulado' WHEN nota_credito_ventas_cab.estado = 'P' THEN 'Emitida' END as estado"),
                DB::raw("nota_credito_ventas_cab.nume_serie||' '||lpad(CAST(nota_credito_ventas_cab.nro_nota_credito AS CHAR), 7, '0') as nro_comp"),
                DB::raw("CASE WHEN clientes.tipo_persona = 'F' THEN clientes.nombre||', '||clientes.apellido ELSE clientes.razon_social END AS cliente"),
                DB::raw('sucursales.nombre AS sucursal'),
                DB::raw("SUM(CASE WHEN nota_credito_ventas_cab.estado = 'A' THEN 0 ELSE nota_credito_ventas_det.monto_descuento END) as total_descuento"),
                DB::raw("SUM(CASE WHEN nota_credito_ventas_cab.estado = 'A' THEN 0 ELSE nota_credito_ventas_det.monto_exenta END) as total_exenta"),
                DB::raw("SUM(CASE WHEN nota_credito_ventas_cab.estado = 'A' THEN 0 ELSE nota_credito_ventas_det.monto_gravada END) as total_gravada"), 
                DB::raw("SUM(CASE WHEN nota_credito_ventas_cab.estado = 'A' THEN 0 ELSE nota_credito_ventas_det.monto_iva END) as total_iva"),
                DB::raw("SUM(CASE WHEN nota_credito_ventas_cab.estado = 'A' THEN 0 ELSE nota_credito_ventas_det.monto_total END) as total_comprobante"))
            ->where('nota_credito_ventas_cab.fecha_emision', '>=', $fecha_inicial)
            ->where('nota_credito_ventas_cab.fecha_emision', '<=', $fecha_final);

        if ($incluye_anulados == 1) {
            $facturas = $facturas->where('facturas_ventas_cab.estado', '!=', 'A');
            $notas_credito = $notas_credito->where('nota_credito_ventas_cab.estado', '!=', 'A');
            $incluye_anulados = 'No';
        } else {
            $incluye_anulados = 'Si';
        }

        if ($request->has('cliente_id')) {
            $facturas = $facturas->whereIn('facturas_ventas_cab.cliente_id', $request['cliente_id']);
            $notas_credito = $notas_credito->whereIn('nota_credito_ventas_cab.cliente_id', $request['cliente_id']);
            if (count($request['cliente_id']) > 1) {
                $cliente = 'Multiple seleccion';
            } else {
                $cliente = Cliente::findOrFail($request['cliente_id'][0]);
                $cliente = $cliente->getNombreIndex();
            }
        } else {
            $cliente = 'Todos';
        }

        if ($request->has('sucursal_id')) {
            $facturas = $facturas->whereIn('facturas_ventas_cab.sucursal_id', $request['sucursal_id']);
            $notas_credito = $notas_credito->whereIn('nota_credito_ventas_cab.sucursal_id', $request['sucursal_id']);
            if (count($request['sucursal_id']) > 1) {
                $sucursal = 'Multiple seleccion';
            } else {
                $sucursal = Sucursal::findOrFail($request['sucursal_id'][0]);
                $sucursal = $sucursal->getNombre();
            }
        } else {
            $sucursal = 'Todas';
        }

        if ($request->has('vendedor_id')) {
            $facturas = $facturas->where('facturas_ventas_cab.usuario_id', $request['vendedor_id']);
            $notas_credito = $notas_credito->where('nota_credito_ventas_cab.usuario_id', $request['vendedor_id']);
            $vendedor = User::findOrFail($request['vendedor_id']);
            $vendedor = $vendedor->empleado->getNombre().', '.$vendedor->empleado->getApellido();
        } else {
            $vendedor = 'Todos';
        }

        $facturas = $facturas->groupBy('facturas_ventas_cab.fecha_emision', 'facturas_ventas_cab.nume_serie', 'facturas_ventas_cab.nro_factura', 'facturas_ventas_cab.estado', 'sucursales.nombre', 'clientes.tipo_persona', 'clientes.nombre', 'clientes.apellido', 'clientes.razon_social');
        $notas_credito = $notas_credito->groupBy('nota_credito_ventas_cab.fecha_emision', 'nota_credito_ventas_cab.nume_serie', 'nota_credito_ventas_cab.nro_nota_credito', 'nota_credito_ventas_cab.estado', 'sucursales.nombre', 'clientes.tipo_persona', 'clientes.nombre', 'clientes.apellido', 'clientes.razon_social');
        $registros = $facturas->union($notas_credito);
        $registros = $registros->orderBy('fecha_emision', 'ASC')->orderBy('nro_comp', 'ASC');
    	$facturas = $registros->get();

    	$pdf = PDF::loadView('reportesVentas.ventasReporte', compact('facturas', 'fecha_inicial', 'fecha_final', 'sucursal', 'cliente', 'vendedor', 'incluye_anulados'))->setPaper('a4', 'landscape');

        return $pdf->stream('ReporteDeVentas.pdf',array('Attachment'=>0));
    }
}
