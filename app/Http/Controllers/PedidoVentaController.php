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

        if (!empty('sucursal')) {
            $request['sucursal_id'] = $sucursal->getId();
        }

        $rules = [
            'lista_precio_id' => 'required',
            'sucursal_id' => 'required',
            'moneda_id' => 'required',
            'valor_cambio' => 'required',
            'fecha_emision' => 'required|date_format:d/m/Y',
            'tab_articulo_id' => 'required|array|min:1|max:'.PedidoVentaCab::MAX_LINEAS_DETALLE,
            'tab_cantidad' => 'required|array|min:1',
            'tab_precio_unitario' => 'required|array|min:1',
            'tab_monto_descuento' => 'required|array|min:1',
        ];

        $mensajes = [
            'tab_articulo_id.min' => 'Como mínimo se debe asignar :min producto(s) al pedido!',
            'tab_articulo_id.max' => 'Ha superado la cantidad máxima de líneas en un pedido. La cantidad máxima es de :max!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes)->validate();
        /*if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }*/
        foreach ($request['tab_subtotal'] as $subtotal) {
            $total = $total + str_replace('.', '', $subtotal);
        }

        $cabecera->setNroPedido();
        $cabecera->setClienteId($request['cliente_id']);
        $cabecera->setSucursalId($request['sucursal_id']);
        $cabecera->setMonedaId($request['moneda_id']);
        $cabecera->setValorCambio($request['valor_cambio']);
        $cabecera->setFechaEmision($request['fecha_emision']);
        $cabecera->setMontoTotal($total);
        $cabecera->setComentario();

        $cabecera->save();

        return $request;
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
