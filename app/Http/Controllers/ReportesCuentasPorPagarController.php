<?php

namespace App\Http\Controllers;

use Validator;
use App\Empresa;
use App\Proveedor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class ReportesCuentasPorPagarController extends Controller
{
    public function viewExtractoProveedor(){
    	$fecha_actual = date("d/m/Y");;
    	return view('reportescuentasporpagar.extractoproveedorfiltros', compact('fecha_actual'));
    }

    public function verExtractoProveedor(Request $request){
    	
    	$rules = [
            'proveedor_id' => 'required',
            'fecha_inicial' => 'required|before_or_equal:fecha_final',
            'fecha_final' => 'required',
        ];

        $mensajes = [
            'proveedor_id.required' => 'Debe seleccionar un Proveedor para ejecutar el reporte!',
            'fecha_inicial.required' => 'Debe especificar la fecha inicial para ejecutar el reporte!',
            'fecha_final.required' => 'Debe especificar la fecha final para ejecutar el reporte!',
            'fecha_inicial.before_or_equal' => 'La fecha inicial no puede ser mayor a la fecha final!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes)->validate();

        $proveedor_id = $request['proveedor_id'];
        $fecha_inicial = $request['fecha_inicial'];
        $fecha_final = $request['fecha_final'];

       $proveedor = Proveedor::findOrFail($proveedor_id);
        $codigo_establecimiento = Empresa::first();
        $codigo_establecimiento = $codigo_establecimiento->getCodigoEstablecimiento();
        
        /*$facturas = DB::table('cuenta_proveedores')
        	->join('compras_cab', 'cuenta_proveedores.comprobante_id', '=', 'compras_cab.id')
        	->join('sucursales', 'compras_cab.sucursal_id', '=', 'sucursales.id')
        	->crossJoin('empresa')
        	->select(DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
        		DB::raw("'Factura' as descripcion"), 
        		DB::raw("empresa.codigo_establecimiento||'-'||sucursales.codigo_punto_expedicion||' '||lpad(CAST(compras_cab.nro_factura AS CHAR), 7, '0') as nro_comp"), 
        		DB::raw("'0' as credito"), 
        		DB::raw("TO_CHAR(ROUND(compras_cab.monto_total), '999G999G999') as debito"), 'cuenta_proveedores.created_at')
        	->where('cuenta_proveedores.tipo_comprobante', 'F')
        	->where('compras_cab.proveedor_id', $proveedor_id)
        	->where('compras_cab.fecha_emision', '<=', $fecha_final);*/
        $facturas = DB::table('cuenta_proveedores')
            ->join('compras_cab', 'cuenta_proveedores.comprobante_id', '=', 'compras_cab.id')
            ->select(DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
                DB::raw("'Factura' as descripcion"), 
                DB::raw("compras_cab.nro_factura as nro_comp"), 
                DB::raw("'0' as credito"), 
                DB::raw("TO_CHAR(ROUND(compras_cab.monto_total), '999G999G999') as debito"), 'cuenta_proveedores.created_at')
            ->where('cuenta_proveedores.tipo_comprobante', 'F')
            ->where('compras_cab.proveedor_id', $proveedor_id)
            ->where('compras_cab.fecha_emision', '<=', $fecha_final)
            ->where('compras_cab.fecha_emision', '>=', $fecha_inicial);

        $orden_pago = DB::table('cuenta_proveedores')
            ->join('orden_pago', 'cuenta_proveedores.comprobante_id', '=', 'orden_pago.id')
            ->select(DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
                DB::raw("'Orden de Pago' as descripcion"), 
                DB::raw("CAST(orden_pago.nro_orden as varchar) as nro_comp"),  
                DB::raw("TO_CHAR(ROUND(orden_pago.monto_total), '999G999G999') as credito"), 
                DB::raw("'0' as debito"), 'cuenta_proveedores.created_at')
                ->where('cuenta_proveedores.tipo_comprobante', 'P')
            ->where('orden_pago.proveedor_id', $proveedor_id)
            ->where('orden_pago.fecha_emision', '<=', $fecha_final)
            ->where('orden_pago.fecha_emision', '>=', $fecha_inicial);

        /*$registros = DB::table('cuenta_proveedores')
        	->join('nota_credito_compras_cab', 'cuenta_proveedores.comprobante_id', '=', 'nota_credito_compras_cab.id')
        	->crossJoin('empresa')
        	->join('sucursales', 'nota_credito_compras_cab.sucursal_id', '=', 'sucursales.id')
        	->select(DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
        		DB::raw("'Nota de Crédito' as descripcion"), 
        		DB::raw("empresa.codigo_establecimiento||'-'||sucursales.codigo_punto_expedicion||' '||lpad(CAST(nota_credito_compras_cab.nro_nota_credito AS CHAR), 7, '0') as nro_comp"), 
        		DB::raw("TO_CHAR(ROUND(nota_credito_compras_cab.monto_total), '999G999G999') as credito"), 
        		DB::raw("'0' as debito"), 'cuenta_proveedores.created_at')
        	->where('cuenta_proveedores.tipo_comprobante', 'N')
        	->where('nota_credito_compras_cab.proveedor_id', $proveedor_id)
        	->where('nota_credito_compras_cab.fecha_emision', '<=', $fecha_final)
        	->union($facturas);*/
        $registros = DB::table('cuenta_proveedores')
            ->join('nota_credito_compras_cab', 'cuenta_proveedores.comprobante_id', '=', 'nota_credito_compras_cab.id')
            ->select(DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
                DB::raw("'Nota de Crédito' as descripcion"), 
                DB::raw("nota_credito_compras_cab.nro_nota_credito as nro_comp"), 
                DB::raw("TO_CHAR(ROUND(nota_credito_compras_cab.monto_total), '999G999G999') as credito"), 
                DB::raw("'0' as debito"), 'cuenta_proveedores.created_at')
            ->where('cuenta_proveedores.tipo_comprobante', 'N')
            ->where('nota_credito_compras_cab.proveedor_id', $proveedor_id)
            ->where('nota_credito_compras_cab.fecha_emision', '<=', $fecha_final)
            ->where('nota_credito_compras_cab.fecha_emision', '>=', $fecha_inicial)
            ->union($facturas)
            ->union($orden_pago);
        $registros->orderBy('created_at');

        $total_debito = DB::table('cuenta_proveedores')
        	->join('compras_cab', 'cuenta_proveedores.comprobante_id', '=', 'compras_cab.id')
        	->where('cuenta_proveedores.tipo_comprobante', 'F')
        	->where('compras_cab.proveedor_id', $proveedor_id)
            ->where('compras_cab.fecha_emision', '>=', $fecha_inicial)
        	->where('compras_cab.fecha_emision', '<=', $fecha_final)
        	->where('compras_cab.estado', '<>', 'A')
        	->sum('compras_cab.monto_total');
        
        $total_credito = DB::table('cuenta_proveedores')
        	->join('nota_credito_compras_cab', 'cuenta_proveedores.comprobante_id', '=', 'nota_credito_compras_cab.id')
        	->where('cuenta_proveedores.tipo_comprobante', 'N')
        	->where('nota_credito_compras_cab.proveedor_id', $proveedor_id)
            ->where('nota_credito_compras_cab.fecha_emision', '>=', $fecha_inicial)
        	->where('nota_credito_compras_cab.fecha_emision', '<=', $fecha_final)
            ->sum('nota_credito_compras_cab.monto_total');
            
        $total_op = DB::table('cuenta_proveedores')
        	->join('orden_pago', 'cuenta_proveedores.comprobante_id', '=', 'orden_pago.id')
        	->where('cuenta_proveedores.tipo_comprobante', 'P')
        	->where('orden_pago.proveedor_id', $proveedor_id)
            ->where('orden_pago.fecha_emision', '>=', $fecha_inicial)
        	->where('orden_pago.fecha_emision', '<=', $fecha_final)
        	->sum('orden_pago.monto_total');

        $facturas_saldo_ante = DB::table('cuenta_proveedores')
            ->join('compras_cab', 'cuenta_proveedores.comprobante_id', '=', 'compras_cab.id')
            ->where('cuenta_proveedores.tipo_comprobante', 'F')
            ->where('compras_cab.proveedor_id', $proveedor_id)
            ->where('compras_cab.fecha_emision', '<', $fecha_inicial)
            ->sum('compras_cab.monto_total');

        $notas_credito_saldo_ante = DB::table('cuenta_proveedores')
            ->join('nota_credito_compras_cab', 'cuenta_proveedores.comprobante_id', '=', 'nota_credito_compras_cab.id')
            ->where('cuenta_proveedores.tipo_comprobante', 'N')
            ->where('nota_credito_compras_cab.proveedor_id', $proveedor_id)
            ->where('nota_credito_compras_cab.fecha_emision', '<', $fecha_inicial)
            ->sum('nota_credito_compras_cab.monto_total');

        $orden_pago_saldo_ante = DB::table('cuenta_proveedores')
            ->join('orden_pago', 'cuenta_proveedores.comprobante_id', '=', 'orden_pago.id')
            ->where('cuenta_proveedores.tipo_comprobante', 'P')
            ->where('orden_pago.proveedor_id', $proveedor_id)
            ->where('orden_pago.fecha_emision', '<', $fecha_inicial)
            ->sum('orden_pago.monto_total');

        $saldo_anterior = $facturas_saldo_ante - $notas_credito_saldo_ante - $orden_pago_saldo_ante;
        $registros = $registros->get();
        $saldo = $saldo_anterior + $total_debito - ($total_credito + $total_op);
        $total_credito = number_format(($total_credito + $total_op), 0, ',', '.');
        //$total_debito = $total_credito + $total_op;
        //dd($total_op);
        //dd($total_debito);
        $total_op = number_format($total_op, 0, ',', '.'); // + number_format($total_op, 0, ',', '.');
        $total_debito = number_format($total_debito, 0, ',', '.'); // + number_format($total_op, 0, ',', '.');
        $saldo = number_format($saldo, 0, ',', '.');
        $saldo_anterior = number_format($saldo_anterior, 0, ',', '.');

        $pdf = PDF::loadView('reportescuentasporpagar.extractoproveedorreporte', 
                compact('registros', 'total_credito', 'total_debito', 'proveedor', 
                'fecha_final', 'fecha_inicial', 'saldo', 'saldo_anterior'));

        return $pdf->stream('ExtractoDeProveedor.pdf',array('Attachment'=>0));
    }
}
