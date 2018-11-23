<?php

namespace App\Http\Controllers;

use Validator;
use App\Serie;
use App\Empresa;
use App\Cliente;
use App\DatosDefault;
use App\CuentaCliente;
use App\FacturaVentaCab;
use App\FacturaVentaDet;
use App\ExistenciaArticulo;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class FacturaVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('facturaVenta.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fecha_actual = date("d/m/Y");
        $datos_default = DatosDefault::get()->first();
        $lista_precio = $datos_default->listaPrecio;
        $moneda = $datos_default->moneda;
        $cambio = 1;
        $configuracion_empresa = Empresa::first();
        $sucursal = Auth::user()->empleado->sucursalDefault;
        $vendedor = Auth::user()->empleado;
        $serie = Serie::where('vendedor_id', $vendedor->getId())
            ->where('sucursal_id', $sucursal->getId())
            ->where('tipo_comprobante', 'F')->first();
        $serie_factura = $configuracion_empresa->getCodigoEstablecimiento().'-'.$sucursal->getCodigoPuntoExpedicion();
        $nro_factura = str_pad($serie->getNroActual()+1, 7, "0", STR_PAD_LEFT);
        $clientes = Cliente::where('activo', true)->get();
        return view('facturaVenta.create', compact('fecha_actual', 'moneda', 'lista_precio', 'cambio', 'serie', 'serie_factura', 'nro_factura', 'clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        $sucursal = Auth::user()->empleado->sucursalDefault;
        $usuario = Auth::user();
        $cabecera = new FacturaVentaCab();
        $total = 0;
        $serie = Serie::findOrFail($request['serie_id']);
        $cliente = Cliente::findOrFail($request['cliente_id']);

        

        //Implementar que cuando el cliente se deja en blanco, se busque al registro de cliente ocasional para poder guardarlo

        if (!empty('sucursal')) {
            $request['sucursal_id'] = $sucursal->getId();
        }

        $rules = [
            'tipo_factura' => 'required',
            'serie_id' => 'required',
            'nro_factura' => 'required',
            'lista_precio_id' => 'required',
            'cliente_id' => 'required',
            'sucursal_id' => 'required',
            'moneda_id' => 'required',
            'lista_precio_id' => 'required',
            'valor_cambio' => 'required|numeric|min:1',
            'fecha_emision' => 'required|date_format:d/m/Y',
            'tab_articulo_id' => 'required|array|min:1|max:'.FacturaVentaCab::MAX_LINEAS_DETALLE,
        ];

        $mensajes = [
            'valor_cambio.min' => 'El valor de cambio no puede ser menor que :min !',
            'tab_articulo_id.required' => 'No se puede guardar una factura sin artículos en el detalle!',
            'tab_articulo_id.min' => 'Como mínimo se debe asignar :min producto(s) a la factura!',
            'tab_articulo_id.max' => 'Ha superado la cantidad máxima de líneas en una factura. La cantidad máxima es de :max!',
            'cliente_id.required' => 'Debe seleccionar un cliente!',
        ];

        $request['valor_cambio'] = str_replace('.', '', $request['valor_cambio']);

        $validator = Validator::make($request->all(), $rules, $mensajes)->validate();

        $serie = Serie::findOrFail($request['serie_id']);
        $cliente = Cliente::findOrFail($request['cliente_id']);
        
        /*if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }*/

        for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
            $total = $total + str_replace('.', '', $request['tab_subtotal'][$i]);
        }

        if ($request['tipo_factura'] == 'CR') {
            if ($cliente->getLimiteCredito() < $total) {
                return redirect()->back()->withErrors('La factura supera el límite de crédito del cliente! Su límite es de Gs '.$cliente->getLimiteCreditoNumber())->withInput();
            }
        }

        $cabecera->setTipoFactura($request['tipo_factura']);
        $cabecera->setSerieId($request['serie_id']);
        $cabecera->setNroFactura($request['nro_factura']);
        $cabecera->setClienteId($request['cliente_id']);
        $cabecera->setSucursalId($request['sucursal_id']);
        $cabecera->setMonedaId($request['moneda_id']);
        $cabecera->setListaPrecioId($request['lista_precio_id']);
        $cabecera->setValorCambio($request['valor_cambio']);
        $cabecera->setFechaEmision($request['fecha_emision']);
        $cabecera->setComentario($request['comentario']);
        $cabecera->setMontoTotal($total);
        $cabecera->setUsuarioId($usuario->id);

        $cabecera->save();

        for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
            $detalle = new FacturaVentaDet;
            $detalle->setFacturaCabeceraId($cabecera->getId());
            $detalle->setArticuloId($request['tab_articulo_id'][$i]);
            $detalle->setCantidad(str_replace('.', '', $request['tab_cantidad'][$i]));
            $detalle->setPrecioUnitario(str_replace('.', '', $request['tab_precio_unitario'][$i]));
            $detalle->setPorcentajeDescuento(str_replace('.', '', $request['tab_porcentaje_descuento'][$i]));
            $detalle->setMontoDescuento(str_replace('.', '', $request['tab_monto_descuento'][$i]));
            $detalle->setPorcentajeIva(round(str_replace('.', ',', $request['tab_porcentaje_iva'][$i])), 0);
            $detalle->setMontoExenta(str_replace('.', '', $request['tab_exenta'][$i]));
            $detalle->setMontoGravada(str_replace('.', '', $request['tab_gravada'][$i]));
            $detalle->setMontoIva(str_replace('.', '', $request['tab_iva'][$i]));
            $detalle->setMontoTotal(str_replace('.', '', $request['tab_subtotal'][$i]));
            $detalle->save();

            if ($detalle->articulo->getControlExistencia() == true) {
                //Actualizacion de existencia
                $existencia = ExistenciaArticulo::where('articulo_id', $detalle->articulo->getId())
                    ->where('sucursal_id', $sucursal->getId())->first();
                $existencia->actualizaStock('-', $detalle->getCantidad());
                $existencia->update();
            }
        }

        $serie->setNroActual($serie->getNroActual()+1);
        $serie->update();

        //Actualizacion de saldo cliente
        $cuenta = new CuentaCliente;
        $cuenta->setTipoComprobante('F');
        $cuenta->setComprobanteId($cabecera->getId());
        $cuenta->setMontoComprobante(str_replace('.', '', $cabecera->getMontoTotal()));
        $cuenta->setMontoSaldo(str_replace('.', '', $cabecera->getMontoTotal()));
        $cuenta->save();

        $cliente->setMontoSaldo($cliente->getMontoSaldo()+str_replace('.', '', $cabecera->getMontoTotal()));
        $cliente->update();

        return redirect()->route('facturacionVentas.show', ['facturacionVenta' => $cabecera->getId()])->with('status', 'Factura guardada correctamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $factura_cab = FacturaVentaCab::findOrFail($id);
        return view('facturaVenta.show', compact('factura_cab'));
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

    public function apiFacturacionVentas(){
        $permiso_editar = Auth::user()->can('facturacionVentas.edit');
        //$permiso_eliminar = Auth::user()->can('facturacionVentas.destroy');
        $permiso_ver = Auth::user()->can('facturacionVentas.show');
        $sucursal_actual = Auth::user()->empleado->sucursales->first();
        $facturas = FacturaVentaCab::where('sucursal_id',$sucursal_actual->getId())->get();

        if ($permiso_editar) {
            if ($permiso_ver) {
                return Datatables::of($facturas)
                    ->addColumn('tipo_factura', function($facturas){
                        return $facturas->getTipoFacturaIndex();
                    })
                    ->addColumn('nro_factura', function($facturas){
                        return $facturas->getNroFacturaIndex();
                    })
                    ->addColumn('fecha', function($facturas){
                        return $facturas->getFechaEmision();
                    })
                    ->addColumn('cliente', function($facturas){
                        return $facturas->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($facturas){
                        return $facturas->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($facturas){
                        return $facturas->getMontoTotal();
                    })
                    ->addColumn('estado', function($facturas){
                        if ($facturas->estado == 'P') {
                            return 'Pendiente';
                        } elseif ($facturas->estado == 'C') {
                            return 'Cobrada';
                        } elseif ($facturas->estado == 'A') {
                            return 'Anulada';
                        }
                    })
                    ->addColumn('action', function($facturas){
                        $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $facturas->id .')" class="btn btn-primary btn-sm" title="Ver Factura"><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $facturas->id .')" class="btn btn-warning btn-sm" title="Editar Factura"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        if ($facturas->estado == 'P') {
                            return $puede_ver.$puede_editar;
                        } else {
                            return $puede_ver.$no_puede_editar;
                        }
                    })->make(true);
            } else {
                return Datatables::of($facturas)
                    ->addColumn('tipo_factura', function($facturas){
                        return $facturas->getTipoFacturaIndex();
                    })
                    ->addColumn('fecha', function($facturas){
                        return $facturas->getFechaEmision();
                    })
                    ->addColumn('cliente', function($facturas){
                        return $facturas->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($facturas){
                        return $facturas->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($facturas){
                        return $facturas->getMontoTotal();
                    })
                    ->addColumn('estado', function($facturas){
                        if ($facturas->estado == 'P') {
                            return 'Pendiente';
                        } elseif ($facturas->estado == 'C') {
                            return 'Cobrada';
                        } elseif ($facturas->estado == 'A') {
                            return 'Anulada';
                        }
                    })
                    ->addColumn('action', function($facturas){
                        $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Factura" disabled><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $facturas->id .')" class="btn btn-warning btn-sm" title="Editar Factura"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        if ($facturas->estado == 'P') {
                            return $no_puede_ver.$puede_editar;
                        } else {
                            return $no_puede_ver.$no_puede_editar;
                        }
                    })->make(true);
            }
        } else {
            if ($permiso_ver) {
                return Datatables::of($facturas)
                    ->addColumn('tipo_factura', function($facturas){
                        return $facturas->getTipoFacturaIndex();
                    })
                    ->addColumn('fecha', function($facturas){
                        return $facturas->getFechaEmision();
                    })
                    ->addColumn('cliente', function($facturas){
                        return $facturas->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($facturas){
                        return $facturas->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($facturas){
                        return $facturas->getMontoTotal();
                    })
                    ->addColumn('estado', function($facturas){
                        if ($facturas->estado == 'P') {
                            return 'Pendiente';
                        } elseif ($facturas->estado == 'C') {
                            return 'Cobrada';
                        } elseif ($facturas->estado == 'A') {
                            return 'Anulada';
                        }
                    })
                    ->addColumn('action', function($facturas){
                        $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $facturas->id .')" class="btn btn-primary btn-sm" title="Ver Factura"><i class="fa fa-eye"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        return $puede_ver.$no_puede_editar;
                    })->make(true);
            } else {
                return Datatables::of($facturas)
                    ->addColumn('tipo_factura', function($facturas){
                        return $facturas->getTipoFacturaIndex();
                    })
                    ->addColumn('fecha', function($facturas){
                        return $facturas->getFechaEmision();
                    })
                    ->addColumn('cliente', function($facturas){
                        return $facturas->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($facturas){
                        return $facturas->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($facturas){
                        return $facturas->getMontoTotal();
                    })
                    ->addColumn('estado', function($facturas){
                        if ($facturas->estado == 'P') {
                            return 'Pendiente';
                        } elseif ($facturas->estado == 'C') {
                            return 'Cobrada';
                        } elseif ($facturas->estado == 'A') {
                            return 'Anulada';
                        }
                    })
                    ->addColumn('action', function($facturas){
                        $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Factura" disabled><i class="fa fa-eye"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        return $no_puede_ver.$no_puede_editar;
                    })->make(true);
            }
        }
    }
}
