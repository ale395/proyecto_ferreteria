<?php

namespace App\Http\Controllers;

use Validator;
use App\Serie;
use App\Empresa;
use App\Cliente;
use App\DatosDefault;
use App\CuentaCliente;
use App\FacturaVentaCab;
use App\NotaCreditoVentaCab;
use App\NotaCreditoVentaDet;
use App\ExistenciaArticulo;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class NotaCreditoVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('notaCreditoVenta.index');
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
            ->where('tipo_comprobante', 'N')->first();
        $serie_ncre = $configuracion_empresa->getCodigoEstablecimiento().'-'.$sucursal->getCodigoPuntoExpedicion();
        $nro_ncre = str_pad($serie->getNroActual()+1, 7, "0", STR_PAD_LEFT);
        $nro_ncre_exte = $serie_ncre.' '.$nro_ncre;
        $clientes = Cliente::where('activo', true)->get();
        return view('notaCreditoVenta.create', compact('fecha_actual', 'moneda', 'lista_precio', 'cambio', 'serie', 'serie_ncre', 'nro_ncre', 'clientes', 'nro_ncre_exte'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sucursal = Auth::user()->empleado->sucursalDefault;
        $usuario = Auth::user();
        $cabecera = new NotaCreditoVentaCab();
        $total = 0;
        $array_pedidos = [];
        if ($request['pedidos_id'] != null) {
            $array_pedidos = explode(",",($request['pedidos_id']));
        }

        if (!empty('sucursal')) {
            $request['sucursal_id'] = $sucursal->getId();
        }

        $rules = [
            'tipo_nota_credito' => 'required',
            'serie_id' => 'required',
            'nro_nota_credito' => 'required',
            'cliente_id' => 'required',
            'sucursal_id' => 'required',
            'moneda_id' => 'required',
            'valor_cambio' => 'required|numeric|min:1',
            'fecha_emision' => 'required|date_format:d/m/Y',
            'tab_articulo_id' => 'required|array|min:1|max:'.NotaCreditoVentaCab::MAX_LINEAS_DETALLE,
        ];

        $mensajes = [
            'valor_cambio.min' => 'El valor de cambio no puede ser menor que :min !',
            'tab_articulo_id.required' => 'No se puede guardar una nota de crédito sin registros en el detalle!',
            'tab_articulo_id.min' => 'Como mínimo se debe cargar :min registro(s) a la nota de crédito!',
            'tab_articulo_id.max' => 'Ha superado la cantidad máxima de líneas en una nota de crédito. La cantidad máxima es de :max!',
            'cliente_id.required' => 'Debe seleccionar un cliente!',
        ];

        $request['valor_cambio'] = str_replace('.', '', $request['valor_cambio']);

        $validator = Validator::make($request->all(), $rules, $mensajes)->validate();

        if (count($array_pedidos) == 0) {
            return redirect()->back()->withErrors('No puede guardar una nota de crédito sin relacionar a una factura!')->withInput();
        }

        $serie = Serie::findOrFail($request['serie_id']);
        $cliente = Cliente::findOrFail($request['cliente_id']);

        for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
            $total = $total + str_replace('.', '', $request['tab_subtotal'][$i]);
        }

        foreach ($array_pedidos as $nro_factura) {
            $factura_cab = FacturaVentaCab::findOrFail($nro_factura);
            $total_factura = $factura_cab->getMontoSaldo();
            if ($total_factura < $total) {
                return redirect()->back()->withErrors('La nota de crédito no puede ser mayor al saldo de la factura! El saldo de la factura es de Gs '.$factura_cab->getMontoSaldoFormat())->withInput();
            }
            $cabecera->setFacturaId($nro_factura);
        }

        $cabecera->setTipoNotaCredito($request['tipo_nota_credito']);
        $cabecera->setSerieId($request['serie_id']);
        $cabecera->setNroNotaCredito($request['nro_nota_credito']);
        $cabecera->setClienteId($request['cliente_id']);
        $cabecera->setSucursalId($request['sucursal_id']);
        $cabecera->setMonedaId($request['moneda_id']);
        $cabecera->setValorCambio($request['valor_cambio']);
        $cabecera->setFechaEmision($request['fecha_emision']);
        $cabecera->setComentario($request['comentario']);
        $cabecera->setMontoTotal($total);
        $cabecera->setUsuarioId($usuario->id);

        $cabecera->save();

        for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
            $detalle = new NotaCreditoVentaDet;
            $detalle->setNotaCreditoCabeceraId($cabecera->getId());
            $detalle->setArticuloId($request['tab_articulo_id'][$i]);
            
            $detalle->setCantidad(str_replace(',', '.', str_replace('.', '', $request['tab_cantidad'][$i])));
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
                $existencia->actualizaStock('+', $detalle->getCantidad());
                $existencia->update();
            }
        }

        /*if (count($array_pedidos) > 0) {
            foreach ($array_pedidos as $nro_pedido) {
                $pedido_cab = PedidoVentaCab::findOrFail($nro_pedido);
                $pedido_cab->setEstado('F');
                $pedido_cab->update();

                $pedido_factura = new PedidoFactura;
                $pedido_factura->setPedidoId($pedido_cab->getId());
                $pedido_factura->setFacturaId($cabecera->getId());
                $pedido_factura->save();
            }
        }*/
        /*Actualiza el saldo de la factura relacionada a la nota de credito*/
        foreach ($array_pedidos as $nro_factura) {
            $cuenta_factura = CuentaCliente::where('tipo_comprobante', 'F')
                ->where('comprobante_id', $nro_factura)->first();
            $cuenta_factura->setMontoSaldo($cuenta_factura->getMontoSaldo() - str_replace('.', '', $cabecera->getMontoTotal()));
            $cuenta_factura->update();
        }

        /*Actualiza el numero de comprobante utilizado para la serie*/
        $serie->setNroActual($serie->getNroActual()+1);
        $serie->update();

        //Actualizacion de saldo cliente
        $cuenta = new CuentaCliente;
        $cuenta->setTipoComprobante('N');
        $cuenta->setComprobanteId($cabecera->getId());
        $cuenta->setMontoComprobante(str_replace('.', '', str_replace('.', '', $cabecera->getMontoTotal())*-1));
        $cuenta->setMontoSaldo(0);
        $cuenta->save();

        $cliente->setMontoSaldo($cliente->getMontoSaldo() - str_replace('.', '', $cabecera->getMontoTotal()));
        $cliente->update();

        return redirect()->route('notaCreditoVentas.show', ['notaCreditoVenta' => $cabecera->getId()])->with('status', 'Nota de Crédito guardada correctamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ncre_cab = NotaCreditoVentaCab::findOrFail($id);
        return view('notaCreditoVenta.show', compact('ncre_cab'));
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

    public function apiNotaCreditoVentas(){
        //$permiso_editar = Auth::user()->can('notaCreditoVentas.edit');
        //$permiso_eliminar = Auth::user()->can('notaCreditoVentas.destroy');
        $permiso_ver = Auth::user()->can('notaCreditoVentas.show');
        $sucursal_actual = Auth::user()->empleado->sucursales->first();
        $notas = NotaCreditoVentaCab::where('sucursal_id',$sucursal_actual->getId())->get();

        if ($permiso_ver) {
            return Datatables::of($notas)
                ->addColumn('tipo_nota_cred', function($notas){
                    return $notas->getTipoNotaCreditoIndex();
                })
                ->addColumn('nro_nota_cred', function($notas){
                    return $notas->getNroNotaCreditoIndex();
                })
                ->addColumn('fecha', function($notas){
                    return $notas->getFechaEmision();
                })
                ->addColumn('cliente', function($notas){
                    return $notas->cliente->getNombreIndex();
                })
                ->addColumn('moneda', function($notas){
                    return $notas->moneda->getDescripcion();
                })
                ->addColumn('monto_total', function($notas){
                    return $notas->getMontoTotal();
                })
                ->addColumn('estado', function($notas){
                    if ($notas->estado == 'P') {
                        return 'Cancelada';
                    } elseif ($notas->estado == 'A') {
                        return 'Anulada';
                    }
                })
                ->addColumn('action', function($notas){
                    return '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $notas->id .')" class="btn btn-primary btn-sm" title="Ver Nota de Crédito"><i class="fa fa-eye"></i></a>';
                })->make(true);
        } else {
            return Datatables::of($notas)
                ->addColumn('tipo_nota_cred', function($notas){
                    return $notas->getTipoNotaCreditoIndex();
                })
                ->addColumn('nro_nota_cred', function($notas){
                    return $notas->getNroNotaCreditoIndex();
                })
                ->addColumn('fecha', function($notas){
                    return $notas->getFechaEmision();
                })
                ->addColumn('cliente', function($notas){
                    return $notas->cliente->getNombreIndex();
                })
                ->addColumn('moneda', function($notas){
                    return $notas->moneda->getDescripcion();
                })
                ->addColumn('monto_total', function($notas){
                    return $notas->getMontoTotal();
                })
                ->addColumn('estado', function($notas){
                    if ($notas->estado == 'P') {
                        return 'Cancelada';
                    } elseif ($notas->estado == 'A') {
                        return 'Anulada';
                    }
                })
                ->addColumn('action', function($notas){
                    return '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Nota de Crédito" disabled><i class="fa fa-eye"></i></a> ';
                })->make(true);
        }
    }
}
