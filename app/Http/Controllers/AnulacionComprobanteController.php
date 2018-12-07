<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AnulacionComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('anulacionComprobante.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function apiComprobantesVentas(){
        $sucursal_actual = Auth::user()->empleado->sucursalDefault;
        $facturas = DB::table('facturas_ventas_cab')
            ->join('sucursales', 'facturas_ventas_cab.sucursal_id', '=', 'sucursales.id')
            ->join('clientes', 'facturas_ventas_cab.cliente_id', '=', 'clientes.id')
            ->crossJoin('empresa')
            ->select(DB::raw("'Factura' as tipo_comp"), 
                DB::raw("empresa.codigo_establecimiento||'-'||sucursales.codigo_punto_expedicion||' '||lpad(CAST(facturas_ventas_cab.nro_factura AS CHAR), 7, '0') as nro_comp"),
                DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
                DB::raw("CASE WHEN clientes.tipo_persona = 'F' THEN clientes.nombre||', '||clientes.apellido WHEN clientes.tipo_persona = 'J' THEN clientes.razon_social END as cliente"),
                DB::raw("TO_CHAR(ROUND(facturas_ventas_cab.monto_total), '999G999G999') as monto_total"),
                DB::raw("CASE WHEN facturas_ventas_cab.estado = 'P' THEN 'Pendiente' END as estado"))
            ->where('facturas_ventas_cab.estado', 'P')
            ->where('facturas_ventas_cab.sucursal_id', $sucursal_actual->getId());

        $registros = DB::table('nota_credito_ventas_cab')
            ->join('sucursales', 'nota_credito_ventas_cab.sucursal_id', '=', 'sucursales.id')
            ->join('clientes', 'nota_credito_ventas_cab.cliente_id', '=', 'clientes.id')
            ->crossJoin('empresa')
            ->select(DB::raw("'Nota de Crédito' as tipo_comp"), 
                DB::raw("empresa.codigo_establecimiento||'-'||sucursales.codigo_punto_expedicion||' '||lpad(CAST(nota_credito_ventas_cab.nro_nota_credito AS CHAR), 7, '0') as nro_comp"),
                DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
                DB::raw("CASE WHEN clientes.tipo_persona = 'F' THEN clientes.nombre||', '||clientes.apellido WHEN clientes.tipo_persona = 'J' THEN clientes.razon_social END as cliente"),
                DB::raw("TO_CHAR(ROUND(nota_credito_ventas_cab.monto_total), '999G999G999') as monto_total"),
                DB::raw("CASE WHEN nota_credito_ventas_cab.estado = 'P' THEN 'Pendiente' END as estado"))
            ->where('nota_credito_ventas_cab.estado', 'P')
            ->where('nota_credito_ventas_cab.sucursal_id', $sucursal_actual->getId())
            ->union($facturas);
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
        //$registros->orderBy('created_at');
        $registros = $registros->get();
        return $registros;
    }
}
