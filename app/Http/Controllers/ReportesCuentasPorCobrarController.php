<?php

namespace App\Http\Controllers;

use Validator;
use App\Empresa;
use App\Cliente;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class ReportesCuentasPorCobrarController extends Controller
{
    public function viewExtractoCliente(){
    	$fecha_actual = date("d/m/Y");
    	return view('reportesCuentasPorCobrar.extractoClienteFiltros', compact('fecha_actual'));
    }

    public function verExtractoCliente(Request $request){
    	
    	$rules = [
            'cliente_id' => 'required',
            'fecha_inicial' => 'required|before_or_equal:fecha_final',
            'fecha_final' => 'required',
        ];

        $mensajes = [
            'cliente_id.required' => 'Debe seleccionar un cliente para ejecutar el reporte!',
            'fecha_inicial.required' => 'Debe especificar la fecha inicial para ejecutar el reporte!',
            'fecha_final.required' => 'Debe especificar la fecha final para ejecutar el reporte!',
            'fecha_inicial.before_or_equal' => 'La fecha inicial no puede ser mayor a la fecha final!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes)->validate();

        $cliente_id = $request['cliente_id'];
        $fecha_inicial = $request['fecha_inicial'];
        $fecha_final = $request['fecha_final'];

        $cliente = Cliente::findOrFail($cliente_id);
        $codigo_establecimiento = Empresa::first();
        $codigo_establecimiento = $codigo_establecimiento->getCodigoEstablecimiento();
        
        /*$facturas = DB::table('cuenta_clientes')
        	->join('facturas_ventas_cab', 'cuenta_clientes.comprobante_id', '=', 'facturas_ventas_cab.id')
        	->join('sucursales', 'facturas_ventas_cab.sucursal_id', '=', 'sucursales.id')
        	->crossJoin('empresa')
        	->select(DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
        		DB::raw("'Factura' as descripcion"), 
        		DB::raw("empresa.codigo_establecimiento||'-'||sucursales.codigo_punto_expedicion||' '||lpad(CAST(facturas_ventas_cab.nro_factura AS CHAR), 7, '0') as nro_comp"), 
        		DB::raw("'0' as credito"), 
        		DB::raw("TO_CHAR(ROUND(facturas_ventas_cab.monto_total), '999G999G999') as debito"), 'cuenta_clientes.created_at')
        	->where('cuenta_clientes.tipo_comprobante', 'F')
        	->where('facturas_ventas_cab.cliente_id', $cliente_id)
        	->where('facturas_ventas_cab.fecha_emision', '<=', $fecha_final);*/
        $facturas = DB::table('cuenta_clientes')
            ->join('facturas_ventas_cab', 'cuenta_clientes.comprobante_id', '=', 'facturas_ventas_cab.id')
            ->select(DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
                DB::raw("'Factura' as descripcion"), 
                DB::raw("facturas_ventas_cab.nume_serie||' '||lpad(CAST(facturas_ventas_cab.nro_factura AS CHAR), 7, '0') as nro_comp"), 
                DB::raw("'0' as credito"), 
                DB::raw("TO_CHAR(ROUND(facturas_ventas_cab.monto_total), '999G999G999') as debito"), 'cuenta_clientes.created_at')
            ->where('cuenta_clientes.tipo_comprobante', 'F')
            ->where('cuenta_clientes.cliente_id', $cliente_id)
            ->where('facturas_ventas_cab.fecha_emision', '<=', $fecha_final)
            ->where('facturas_ventas_cab.fecha_emision', '>=', $fecha_inicial);

        /*$registros = DB::table('cuenta_clientes')
        	->join('nota_credito_ventas_cab', 'cuenta_clientes.comprobante_id', '=', 'nota_credito_ventas_cab.id')
        	->crossJoin('empresa')
        	->join('sucursales', 'nota_credito_ventas_cab.sucursal_id', '=', 'sucursales.id')
        	->select(DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
        		DB::raw("'Nota de Crédito' as descripcion"), 
        		DB::raw("empresa.codigo_establecimiento||'-'||sucursales.codigo_punto_expedicion||' '||lpad(CAST(nota_credito_ventas_cab.nro_nota_credito AS CHAR), 7, '0') as nro_comp"), 
        		DB::raw("TO_CHAR(ROUND(nota_credito_ventas_cab.monto_total), '999G999G999') as credito"), 
        		DB::raw("'0' as debito"), 'cuenta_clientes.created_at')
        	->where('cuenta_clientes.tipo_comprobante', 'N')
        	->where('nota_credito_ventas_cab.cliente_id', $cliente_id)
        	->where('nota_credito_ventas_cab.fecha_emision', '<=', $fecha_final)
        	->union($facturas);*/
        $registros = DB::table('cuenta_clientes')
            ->join('nota_credito_ventas_cab', 'cuenta_clientes.comprobante_id', '=', 'nota_credito_ventas_cab.id')
            ->select(DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
                DB::raw("'Nota de Crédito' as descripcion"), 
                DB::raw("nota_credito_ventas_cab.nume_serie||' '||lpad(CAST(nota_credito_ventas_cab.nro_nota_credito AS CHAR), 7, '0') as nro_comp"), 
                DB::raw("TO_CHAR(ROUND(nota_credito_ventas_cab.monto_total), '999G999G999') as credito"), 
                DB::raw("'0' as debito"), 'cuenta_clientes.created_at')
            ->where('cuenta_clientes.tipo_comprobante', 'N')
            ->where('cuenta_clientes.cliente_id', $cliente_id)
            ->where('nota_credito_ventas_cab.fecha_emision', '<=', $fecha_final)
            ->where('nota_credito_ventas_cab.fecha_emision', '>=', $fecha_inicial)
            ->union($facturas);
        $registros->orderBy('created_at');

        $registros = DB::table('cuenta_clientes')
            ->join('cobranza_cab', 'cuenta_clientes.comprobante_id', '=', 'cobranza_cab.id')
            ->select(DB::raw("TO_CHAR(fecha, 'dd/mm/yyyy') as fecha_emision"), 
                DB::raw("'Cobranza' as descripcion"), 
                DB::raw("'N° '||cobranza_cab.id as nro_comp"), 
                DB::raw("TO_CHAR(ROUND(cobranza_cab.monto_total), '999G999G999') as credito"), 
                DB::raw("'0' as debito"), 'cuenta_clientes.created_at')
            ->where('cuenta_clientes.tipo_comprobante', 'C')
            ->where('cuenta_clientes.cliente_id', $cliente_id)
            ->where('cobranza_cab.fecha', '<=', $fecha_final)
            ->where('cobranza_cab.fecha', '>=', $fecha_inicial)
            ->union($registros);

        $total_debito = DB::table('cuenta_clientes')
        	->join('facturas_ventas_cab', 'cuenta_clientes.comprobante_id', '=', 'facturas_ventas_cab.id')
        	->where('cuenta_clientes.tipo_comprobante', 'F')
        	->where('facturas_ventas_cab.cliente_id', $cliente_id)
            ->where('facturas_ventas_cab.fecha_emision', '>=', $fecha_inicial)
        	->where('facturas_ventas_cab.fecha_emision', '<=', $fecha_final)
        	->where('facturas_ventas_cab.estado', '<>', 'A')
        	->sum('facturas_ventas_cab.monto_total');
        
        $total_credito = DB::table('cuenta_clientes')
        	->join('nota_credito_ventas_cab', 'cuenta_clientes.comprobante_id', '=', 'nota_credito_ventas_cab.id')
        	->where('cuenta_clientes.tipo_comprobante', 'N')
        	->where('nota_credito_ventas_cab.cliente_id', $cliente_id)
            ->where('nota_credito_ventas_cab.fecha_emision', '>=', $fecha_inicial)
        	->where('nota_credito_ventas_cab.fecha_emision', '<=', $fecha_final)
        	->where('nota_credito_ventas_cab.estado', '<>', 'A')
        	->sum('nota_credito_ventas_cab.monto_total');

        $total_cobranza = DB::table('cuenta_clientes')
            ->join('cobranza_cab', 'cuenta_clientes.comprobante_id', '=', 'cobranza_cab.id')
            ->where('cuenta_clientes.tipo_comprobante', 'C')
            ->where('cobranza_cab.cliente_id', $cliente_id)
            ->where('cobranza_cab.fecha', '>=', $fecha_inicial)
            ->where('cobranza_cab.fecha', '<=', $fecha_final)
            ->where('cobranza_cab.estado', '<>', 'A')
            ->sum('cobranza_cab.monto_total');

        $facturas_saldo_ante = DB::table('cuenta_clientes')
            ->join('facturas_ventas_cab', 'cuenta_clientes.comprobante_id', '=', 'facturas_ventas_cab.id')
            ->where('cuenta_clientes.tipo_comprobante', 'F')
            ->where('cuenta_clientes.cliente_id', $cliente_id)
            ->where('facturas_ventas_cab.fecha_emision', '<', $fecha_inicial)
            ->where('facturas_ventas_cab.estado', '<>', 'A')
            ->sum('facturas_ventas_cab.monto_total');

        $notas_credito_saldo_ante = DB::table('cuenta_clientes')
            ->join('nota_credito_ventas_cab', 'cuenta_clientes.comprobante_id', '=', 'nota_credito_ventas_cab.id')
            ->where('cuenta_clientes.tipo_comprobante', 'N')
            ->where('cuenta_clientes.cliente_id', $cliente_id)
            ->where('nota_credito_ventas_cab.fecha_emision', '<', $fecha_inicial)
            ->where('nota_credito_ventas_cab.estado', '<>', 'A')
            ->sum('nota_credito_ventas_cab.monto_total');

        $cobranza_saldo_ante = DB::table('cuenta_clientes')
            ->join('cobranza_cab', 'cuenta_clientes.comprobante_id', '=', 'cobranza_cab.id')
            ->where('cuenta_clientes.tipo_comprobante', 'N')
            ->where('cuenta_clientes.cliente_id', $cliente_id)
            ->where('cobranza_cab.fecha', '<', $fecha_inicial)
            ->where('cobranza_cab.estado', '<>', 'A')
            ->sum('cobranza_cab.monto_total');

        $saldo_anterior = $facturas_saldo_ante - $notas_credito_saldo_ante - $cobranza_saldo_ante;
        $registros = $registros->get();
        $saldo = $saldo_anterior + $total_debito - $total_credito - $total_cobranza;
        $total_credito = number_format($total_credito + $total_cobranza, 0, ',', '.');
        $total_debito = number_format($total_debito, 0, ',', '.');
        $saldo = number_format($saldo, 0, ',', '.');
        $saldo_anterior = number_format($saldo_anterior, 0, ',', '.');

        $pdf = PDF::loadView('reportesCuentasPorCobrar.extractoClienteReporte', compact('registros', 'total_credito', 'total_debito', 'cliente', 'fecha_final', 'fecha_inicial', 'saldo', 'saldo_anterior'));

        return $pdf->stream('ExtractoDeCliente.pdf',array('Attachment'=>0));
    }
}
