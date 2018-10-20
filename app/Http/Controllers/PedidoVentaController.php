<?php

namespace App\Http\Controllers;

use Validator;
use App\DatosDefault;
use App\PedidoVentaCab;
use App\PedidoVentaDet;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class PedidoVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        return view('pedidoVenta.create', compact('fecha_actual', 'moneda', 'lista_precio', 'cambio'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sucursal = Auth::user()->empleado->sucursales->first();
        $cabecera = new PedidoVentaCab();
        $total = 0;
        $nro_pedido = PedidoVentaCab::max('nro_pedido');
        //Implementar que cuando el cliente se deja en blanco, se busque al registro de cliente ocasional para poder guardarlo

        if (!empty('sucursal')) {
            $request['sucursal_id'] = $sucursal->getId();
        }

        $rules = [
            'lista_precio_id' => 'required',
            'cliente_id' => 'required',
            'sucursal_id' => 'required',
            'moneda_id' => 'required',
            'valor_cambio' => 'required|numeric|min:1',
            'fecha_emision' => 'required|date_format:d/m/Y',
            'tab_articulo_id' => 'required|array|min:1|max:'.PedidoVentaCab::MAX_LINEAS_DETALLE,
        ];

        $mensajes = [
            'valor_cambio.min' => 'El valor de cambio no puede ser menor que :min !',
            'tab_articulo_id.min' => 'Como mínimo se debe asignar :min producto(s) al pedido!',
            'tab_articulo_id.max' => 'Ha superado la cantidad máxima de líneas en un pedido. La cantidad máxima es de :max!',
        ];

        $request['valor_cambio'] = str_replace('.', '', $request['valor_cambio']);

        $validator = Validator::make($request->all(), $rules, $mensajes)->validate();
        /*if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }*/
        //foreach ($request['tab_subtotal'] as $subtotal) {

        for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
            $total = $total + str_replace('.', '', $request['tab_subtotal'][$i]);
        }

        if (empty('nro_pedido')) {
            $nro_pedido = 1;
        } else {
            $nro_pedido = $nro_pedido + 1;
        }

        $cabecera->setNroPedido($nro_pedido);
        $cabecera->setClienteId($request['cliente_id']);
        $cabecera->setSucursalId($request['sucursal_id']);
        $cabecera->setMonedaId($request['moneda_id']);
        $cabecera->setValorCambio($request['valor_cambio']);
        $cabecera->setFechaEmision($request['fecha_emision']);
        $cabecera->setMontoTotal($total);
        //$cabecera->setComentario();

        $cabecera->save();

        for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
        //foreach ($request['tab_articulo_id'] as $detalle) {
            $detalle = new PedidoVentaDet;
            $detalle->setPedidoCabeceraId($cabecera->getId());
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
        }

        return redirect()->back()->with('status', 'Pedido guardado correctamente!');
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
}
