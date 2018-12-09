<?php

namespace App\Http\Controllers;

use Validator;
use App\CuentaCliente;
use App\FacturaVentaCab;
use App\NotaCreditoVentaCab;
use App\NotaCreditoVentaDet;
use App\ExistenciaArticulo;
use Illuminate\Http\Request;
use App\AnulacionComprobante;
use Yajra\DataTables\Datatables;
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
        $fecha_actual = date("d/m/Y");
        return view('anulacionComprobante.index', compact('fecha_actual'));
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
        $anulacion = new AnulacionComprobante;

        $rules = [
            'motivo_anulacion_id' => 'required',
        ];

        $mensajes = [
            'motivo_anulacion_id.required' => 'De seleccionar el motivo de anulación del comprobante!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes)->validate();

        $anulacion->setTipoComprobante($request['tipo_comprobante']);
        $anulacion->setComprobanteId($request['comprobante_id']);
        $anulacion->setFechaAnulacion($request['fecha_anulacion']);
        $anulacion->setMotivoAnulacionId($request['motivo_anulacion_id']);
        $anulacion->save();

        if ($request['tipo_comprobante'] == 'F') {
            $factura = FacturaVentaCab::findOrFail($request['comprobante_id']);
            $factura->setEstado('A');
            $factura->update();
            //Deberia verificar tambien facturas relacionadas a pedidos
            if ($factura->facturaPedidos->count() > 0) {
                foreach ($factura->facturaPedidos as $factura_pedido) {
                    $pedido = $factura_pedido->pedido;
                    $pedido->setEstado('P');
                    $pedido->update();
                }
                $factura->pedidos()->detach();
            }
            $detalles_fact = $factura->facturaDetalle;
            foreach ($detalles_fact as $detalle) {
                if ($detalle->articulo->getControlExistencia()) {
                    $existencia = ExistenciaArticulo::where('articulo_id', $detalle->articulo->getId())->where('sucursal_id', Auth::user()->empleado->sucursalDefault->getId())->first();
                    $existencia->setCantidad($existencia->getCantidadNumber() + $detalle->getCantidad());
                    $existencia->update();
                }
            }

        } else {
            $nota_credito = NotaCreditoVentaCab::findOrFail($request['comprobante_id']);
            $nota_credito->setEstado('A');
            $nota_credito->update();

            if ($nota_credito->getTipoNotaCredito() == 'DV') {
                $detalles = $nota_credito->notaCreditoDetalle;
                foreach ($detalles as $detalle) {
                    if ($detalle->articulo->getControlExistencia()) {
                        $existencia = ExistenciaArticulo::where('articulo_id', $detalle->articulo->getId())->where('sucursal_id', Auth::user()->empleado->sucursalDefault->getId())
                            ->first();
                        $existencia->setCantidad($existencia->getCantidadNumber() - $detalle->getCantidad());
                        $existencia->update();
                    }
                }
            }

            $factura = $nota_credito->factura;
            $factura->setEstado('P');
            $factura->update();

            $cuenta_cliente_factura = CuentaCliente::where('tipo_comprobante', 'F')
            ->where('comprobante_id', $factura->getId())
            ->first();
            $saldo = $cuenta_cliente_factura->getMontoSaldo() + $nota_credito->getMontoTotalNumber();
            $cuenta_cliente_factura->setMontoSaldo($saldo);
            $cuenta_cliente_factura->update();
        }

        $cuenta_cliente = CuentaCliente::where('tipo_comprobante', $request['tipo_comprobante'])
            ->where('comprobante_id', $request['comprobante_id'])
            ->first();
        $cuenta_cliente->delete();

        return;
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
            ->select('facturas_ventas_cab.id',DB::raw("'Factura' as tipo_comp"), 
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
            ->select('nota_credito_ventas_cab.id', DB::raw("'Nota de Crédito' as tipo_comp"), 
                DB::raw("empresa.codigo_establecimiento||'-'||sucursales.codigo_punto_expedicion||' '||lpad(CAST(nota_credito_ventas_cab.nro_nota_credito AS CHAR), 7, '0') as nro_comp"),
                DB::raw("TO_CHAR(fecha_emision, 'dd/mm/yyyy') as fecha_emision"), 
                DB::raw("CASE WHEN clientes.tipo_persona = 'F' THEN clientes.nombre||', '||clientes.apellido WHEN clientes.tipo_persona = 'J' THEN clientes.razon_social END as cliente"),
                DB::raw("TO_CHAR(ROUND(nota_credito_ventas_cab.monto_total), '999G999G999') as monto_total"),
                DB::raw("CASE WHEN nota_credito_ventas_cab.estado = 'P' THEN 'Pendiente' END as estado"))
            ->where('nota_credito_ventas_cab.estado', 'P')
            ->where('nota_credito_ventas_cab.sucursal_id', $sucursal_actual->getId())
            ->union($facturas);
        
        $registros->orderBy('fecha_emision');
        $registros = $registros->get();
        return Datatables::of($registros)
            ->addColumn('action', function($registros){
                $factura = '<a data-toggle="tooltip" data-placement="top" onclick="showFactura('.$registros->id.')" class="btn btn-default btn-sm" title="Ver Comprobante"><i class="fa fa-eye"></i></a> '.'<a data-toggle="tooltip" data-placement="top" onclick="anularFactura('. $registros->id .')" class="btn btn-danger btn-sm" title="Anular comprobante"><i class="fa fa-minus-circle"></i></a> ';
                $notaCredito = '<a data-toggle="tooltip" data-placement="top" onclick="showNotaCredito('.$registros->id.')" class="btn btn-default btn-sm" title="Ver Comprobante"><i class="fa fa-eye"></i></a> '.'<a data-toggle="tooltip" data-placement="top" onclick="anularNotaCredito('. $registros->id .')" class="btn btn-danger btn-sm" title="Anular comprobante"><i class="fa fa-minus-circle"></i></a> ';
                if ($registros->tipo_comp == 'Factura') {
                    return $factura;
                } else {
                    return $notaCredito;
                }
            })->make(true);
    }
}
