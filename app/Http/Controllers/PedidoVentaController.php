<?php

namespace App\Http\Controllers;

use Validator;
use App\DatosDefault;
use App\PedidoVentaCab;
use App\PedidoVentaDet;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
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
        return view('pedidoVenta.index');
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
            'lista_precio_id' => 'required',
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
        $cabecera->setListaPrecioId($request['lista_precio_id']);
        $cabecera->setValorCambio($request['valor_cambio']);
        $cabecera->setFechaEmision($request['fecha_emision']);
        $cabecera->setComentario($request['comentario']);
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

        return redirect()->route('pedidosVentas.show', ['pedidosVenta' => $cabecera->getId()])->with('status', 'Pedido guardado correctamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pedido_cab = PedidoVentaCab::findOrFail($id);
        return view('pedidoVenta.show', compact('pedido_cab'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pedido_cab = PedidoVentaCab::findOrFail($id);
        return view('pedidoVenta.edit', compact('pedido_cab'));
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
        //return $request;
        $cabecera = PedidoVentaCab::findOrFail($id);
        $sucursal = Auth::user()->empleado->sucursales->first();
        $total = 0;

        if (!empty('sucursal')) {
            $request['sucursal_id'] = $sucursal->getId();
        }

        $rules = [
            'cliente_id' => 'required',
            'sucursal_id' => 'required',
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

        for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
            $total = $total + str_replace('.', '', $request['tab_subtotal'][$i]);
        }

        $cabecera->setEstado($request['estado']);
        $cabecera->setClienteId($request['cliente_id']);
        $cabecera->setFechaEmision($request['fecha_emision']);
        $cabecera->setMontoTotal($total);
        $cabecera->setComentario($request['comentario']);

        $cabecera->update();
        $cabecera->pedidosDetalle()->delete();

        for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
        //foreach ($request['tab_articulo_id'] as $detalle) {
            $detalle = new PedidoVentaDet;
            $detalle->setPedidoCabeceraId($cabecera->getId());
            $detalle->setArticuloId($request['tab_articulo_id'][$i]);
            $detalle->setCantidad($request['tab_cantidad'][$i]);
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

        return redirect()->route('pedidosVentas.show', ['pedidosVenta' => $cabecera->getId()])->with('status', 'Pedido guardado correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pedido = PedidoVentaCab::findOrFail($id);
        $pedido->pedidosDetalle()->delete();
        return PedidoVentaCab::destroy($id);
    }

    public function apiPedidosCliente($cliente_id){
        if (empty($cliente_id)) {
            return [];
        } else {
            $pedidos = PedidoVentaCab::where('cliente_id', $cliente_id)->
                where('estado', 'P')->get();
            return Datatables::of($pedidos)
                    ->addColumn('nro_pedido', function($pedidos){
                        return $pedidos->getNroPedido();
                    })
                    ->addColumn('fecha', function($pedidos){
                        return $pedidos->getFechaEmision();
                    })
                    ->addColumn('moneda', function($pedidos){
                        return $pedidos->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($pedidos){
                        return $pedidos->getMontoTotal();
                    })
                    ->addColumn('comentario', function($pedidos){
                        return $pedidos->getComentario();
                    })->make(true);
        }
        
    }

    public function apiPedidosDetalles($array_pedidos){
        $cast_array = explode(",",($array_pedidos));
        /*$array_pedidos_agrupados = [];
        $detalles = PedidoVentaDet::whereIn('pedido_cab_id',$cast_array)->get();
        $group_by = $detalles->groupBy('articulo_id');
        dd(array($group_by));
        foreach ($group_by as $registro) {
            $pedido_agrupado = new PedidoVentaDet;
            $pedido_agrupado->setArticuloId($registro[0]);
            array_push($array_pedidos_agrupados, $pedido_agrupado);
        }
        return $array_pedidos_agrupados;*/

        /*PROBANDO CON DB*/
        $pedidos = DB::table('pedidos_ventas_det')
            ->join('pedidos_ventas_cab', 'pedidos_ventas_det.pedido_cab_id', '=', 'pedidos_ventas_cab.id')
            ->leftJoin('existencia_articulos', 'pedidos_ventas_det.articulo_id', '=', 'existencia_articulos.articulo_id')
            ->select('pedidos_ventas_det.articulo_id', 'pedidos_ventas_det.porcentaje_iva', 
            DB::raw('ROUND(AVG(existencia_articulos.cantidad), 2) as cantidad_existencia'),
            DB::raw('ROUND(MIN(pedidos_ventas_det.precio_unitario), 2) as precio_unitario'),
            DB::raw('ROUND(MAX(pedidos_ventas_det.porcentaje_descuento), 2) as porcentaje_descuento'),
            DB::raw('ROUND(SUM(pedidos_ventas_det.cantidad), 2) as cantidad'), 
            DB::raw('ROUND(SUM(pedidos_ventas_det.monto_descuento), 2) as monto_descuento'), 
            DB::raw('ROUND(AVG(pedidos_ventas_det.monto_exenta), 2) as monto_exenta'), 
            DB::raw('ROUND(AVG(pedidos_ventas_det.monto_gravada), 2) as monto_gravada'), 
            DB::raw('ROUND(AVG(pedidos_ventas_det.monto_iva), 2) as monto_iva'), 
            DB::raw('ROUND(AVG(pedidos_ventas_det.monto_total), 2) as monto_total'))
            ->whereIn('pedidos_ventas_det.pedido_cab_id', $cast_array)
            ->where('pedidos_ventas_cab.estado', 'P')
            ->where('existencia_articulos.sucursal_id', Auth::user()->empleado->sucursalDefault->getId())
            ->groupBy('pedidos_ventas_det.articulo_id', 'pedidos_ventas_det.porcentaje_iva', 'existencia_articulos.cantidad')
            ->get();
        return $pedidos;
    }

    public function apiPedidosVentas(){
        $permiso_editar = Auth::user()->can('pedidosVentas.edit');
        $permiso_eliminar = Auth::user()->can('pedidosVentas.destroy');
        $permiso_ver = Auth::user()->can('pedidosVentas.show');
        $pedidos = PedidoVentaCab::all();
        $estados_editables = array('C', 'P', 'V');
        //el listado de pedidos debería ser filtrado por la sucursal actual

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                if ($permiso_ver) {
                    return Datatables::of($pedidos)
                    ->addColumn('fecha', function($pedidos){
                        return $pedidos->getFechaEmision();
                    })
                    ->addColumn('cliente', function($pedidos){
                        return $pedidos->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($pedidos){
                        return $pedidos->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($pedidos){
                        return $pedidos->getMontoTotal();
                    })
                    ->addColumn('estado', function($pedidos){
                        if ($pedidos->estado == 'P') {
                            return 'Pendiente';
                        } elseif ($pedidos->estado == 'F') {
                            return 'Facturado';
                        } elseif ($pedidos->estado == 'C') {
                            return 'Cancelado';
                        } elseif ($pedidos->estado == 'V') {
                            return 'Vencido';
                        }
                    })
                    ->addColumn('action', function($pedidos){
                        $puede_agregar = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $pedidos->id .')" class="btn btn-primary btn-sm" title="Ver Pedido"><i class="fa fa-eye"></i></a> ';
                        $no_puede_agregar = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Pedido" disabled><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $pedidos->id .')" class="btn btn-warning btn-sm" title="Editar Pedido"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Pedido" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        $puede_borrar = '<a data-toggle="tooltip" data-placement="top" onclick="deleteData('. $pedidos->id .')" class="btn btn-danger btn-sm" title="Eliminar Pedido"><i class="fa fa-trash-o"></i></a>';
                        $no_puede_borrar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-sm" title="Eliminar Pedido" disabled><i class="fa fa-trash-o"></i></a>';
                        if ($pedidos->estado == 'F') {
                            return $puede_agregar.$no_puede_editar.$no_puede_borrar;
                        } else {
                            return $puede_agregar.$puede_editar.$puede_borrar;
                        }
                    })->make(true);
                } else {
                    return Datatables::of($pedidos)
                    ->addColumn('fecha', function($pedidos){
                        return $pedidos->getFechaEmision();
                    })
                    ->addColumn('cliente', function($pedidos){
                        return $pedidos->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($pedidos){
                        return $pedidos->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($pedidos){
                        return $pedidos->getMontoTotal();
                    })
                    ->addColumn('estado', function($pedidos){
                        if ($pedidos->estado == 'P') {
                            return 'Pendiente';
                        } elseif ($pedidos->estado == 'F') {
                            return 'Facturado';
                        } elseif ($pedidos->estado == 'C') {
                            return 'Cancelado';
                        } elseif ($pedidos->estado == 'V') {
                            return 'Vencido';
                        }
                    })
                    ->addColumn('action', function($pedidos){
                        if ($pedidos->estado == 'F') {
                            return '<a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-sm" title="Ver Pedido"  disabled><i class="fa fa-eye"></i></a> ' .'<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $pedidos->id .')" class="btn btn-warning btn-sm" title="Editar Pedido"><i class="fa fa-pencil-square-o"></i></a> ' .
                                '<a data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-sm" title="Eliminar Pedido" disabled><i class="fa fa-trash-o"></i></a>';
                        } else {
                            return '<a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-sm" title="Ver Pedido"  disabled><i class="fa fa-eye"></i></a> ' .'<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $pedidos->id .')" class="btn btn-warning btn-sm" title="Editar Pedido"><i class="fa fa-pencil-square-o"></i></a> ' .
                                '<a data-toggle="tooltip" data-placement="top" onclick="deleteData('. $pedidos->id .')" class="btn btn-danger btn-sm" title="Eliminar Pedido"><i class="fa fa-trash-o"></i></a>';
                        }
                    })->make(true);
                }
            } else {
                if ($permiso_ver) {
                    return Datatables::of($pedidos)
                    ->addColumn('fecha', function($pedidos){
                        return $pedidos->getFechaEmision();
                    })
                    ->addColumn('cliente', function($pedidos){
                        return $pedidos->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($pedidos){
                        return $pedidos->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($pedidos){
                        return $pedidos->getMontoTotal();
                    })
                    ->addColumn('estado', function($pedidos){
                        if ($pedidos->estado == 'P') {
                            return 'Pendiente';
                        } elseif ($pedidos->estado == 'F') {
                            return 'Facturado';
                        } elseif ($pedidos->estado == 'C') {
                            return 'Cancelado';
                        } elseif ($pedidos->estado == 'V') {
                            return 'Vencido';
                        }
                    })
                    ->addColumn('action', function($pedidos){
                        return '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $pedidos->id .')" class="btn btn-primary btn-sm" title="Ver Pedido"><i class="fa fa-eye"></i></a> ' .'<a data-toggle="tooltip" data-placement="top" title="Editar Pedido" onclick="editForm('. $pedidos->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a data-toggle="tooltip" data-placement="top" title="Eliminar Pedido" class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                } else{
                    return Datatables::of($pedidos)
                    ->addColumn('fecha', function($pedidos){
                        return $pedidos->getFechaEmision();
                    })
                    ->addColumn('cliente', function($pedidos){
                        return $pedidos->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($pedidos){
                        return $pedidos->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($pedidos){
                        return $pedidos->getMontoTotal();
                    })
                    ->addColumn('estado', function($pedidos){
                        if ($pedidos->estado == 'P') {
                            return 'Pendiente';
                        } elseif ($pedidos->estado == 'F') {
                            return 'Facturado';
                        } elseif ($pedidos->estado == 'C') {
                            return 'Cancelado';
                        } elseif ($pedidos->estado == 'V') {
                            return 'Vencido';
                        }
                    })
                    ->addColumn('action', function($pedidos){
                        return '<a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-sm" title="Ver Pedido" disabled><i class="fa fa-eye"></i></a> ' .'<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $pedidos->id .')" class="btn btn-warning btn-sm" title="Editar Pedido"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-sm" title="Eliminar Pedido" disabled><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                }
            }
        } elseif ($permiso_eliminar) {
            if ($permiso_ver) {
                return Datatables::of($pedidos)
                    ->addColumn('fecha', function($pedidos){
                        return $pedidos->getFechaEmision();
                    })
                    ->addColumn('cliente', function($pedidos){
                        return $pedidos->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($pedidos){
                        return $pedidos->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($pedidos){
                        return $pedidos->getMontoTotal();
                    })
                    ->addColumn('estado', function($pedidos){
                        if ($pedidos->estado == 'P') {
                            return 'Pendiente';
                        } elseif ($pedidos->estado == 'F') {
                            return 'Facturado';
                        } elseif ($pedidos->estado == 'C') {
                            return 'Cancelado';
                        } elseif ($pedidos->estado == 'V') {
                            return 'Vencido';
                        }
                    })
                ->addColumn('action', function($pedidos){
                    if ($pedidos->estado == 'F') {
                        return '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $pedidos->id .')" class="btn btn-primary btn-sm" title="Ver Pedido"><i class="fa fa-eye"></i></a> ' .'<a data-toggle="tooltip" data-placement="top" title="Editar Pedido" class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i></a> ' .
                           '<a data-toggle="tooltip" data-placement="top" title="Eliminar Pedido" class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i></a>';
                    } else {
                        return '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $pedidos->id .')" class="btn btn-primary btn-sm" title="Ver Pedido"><i class="fa fa-eye"></i></a> ' .'<a data-toggle="tooltip" data-placement="top" title="Editar Pedido" class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i></a> ' .
                           '<a data-toggle="tooltip" data-placement="top" title="Eliminar Pedido" onclick="deleteData('. $pedidos->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a>';
                       }
                })->make(true);
            } else{
                return Datatables::of($pedidos)
                    ->addColumn('fecha', function($pedidos){
                        return $pedidos->getFechaEmision();
                    })
                    ->addColumn('cliente', function($pedidos){
                        return $pedidos->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($pedidos){
                        return $pedidos->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($pedidos){
                        return $pedidos->getMontoTotal();
                    })
                    ->addColumn('estado', function($pedidos){
                        if ($pedidos->estado == 'P') {
                            return 'Pendiente';
                        } elseif ($pedidos->estado == 'F') {
                            return 'Facturado';
                        } elseif ($pedidos->estado == 'C') {
                            return 'Cancelado';
                        } elseif ($pedidos->estado == 'V') {
                            return 'Vencido';
                        }
                    })
                ->addColumn('action', function($pedidos){
                    if ($pedidos->estado == 'F') {
                        return '<a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-sm" title="Ver Pedido" disabled><i class="fa fa-eye"></i></a> ' .'<a data-toggle="tooltip" data-placement="top" title="Editar Pedido" class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i></a> ' .
                           '<a data-toggle="tooltip" data-placement="top" title="Eliminar Pedido" class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i></a>';
                    } else {
                        return '<a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-sm" title="Ver Pedido" disabled><i class="fa fa-eye"></i></a> ' .'<a data-toggle="tooltip" data-placement="top" title="Editar Pedido" class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i></a> ' .
                           '<a data-toggle="tooltip" data-placement="top" title="Eliminar Pedido" onclick="deleteData('. $pedidos->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a>';
                       }
                })->make(true);
            }
        } else {
            if ($permiso_ver) {
                return Datatables::of($pedidos)
                    ->addColumn('fecha', function($pedidos){
                        return $pedidos->getFechaEmision();
                    })
                    ->addColumn('cliente', function($pedidos){
                        return $pedidos->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($pedidos){
                        return $pedidos->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($pedidos){
                        return $pedidos->getMontoTotal();
                    })
                    ->addColumn('estado', function($pedidos){
                        if ($pedidos->estado == 'P') {
                            return 'Pendiente';
                        } elseif ($pedidos->estado == 'F') {
                            return 'Facturado';
                        } elseif ($pedidos->estado == 'C') {
                            return 'Cancelado';
                        } elseif ($pedidos->estado == 'V') {
                            return 'Vencido';
                        }
                    })
                ->addColumn('action', function($pedidos){
                    return '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $pedidos->id .')" class="btn btn-primary btn-sm" title="Ver Pedido"><i class="fa fa-eye"></i></a> ' .'<a data-toggle="tooltip" data-placement="top" title="Editar Pedido" class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i></a> ' .
                           '<a data-toggle="tooltip" data-placement="top" title="Eliminar Pedido" class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i></a>';
                })->make(true);
            } else{
                return Datatables::of($pedidos)
                    ->addColumn('fecha', function($pedidos){
                        return $pedidos->getFechaEmision();
                    })
                    ->addColumn('cliente', function($pedidos){
                        return $pedidos->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($pedidos){
                        return $pedidos->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($pedidos){
                        return $pedidos->getMontoTotal();
                    })
                    ->addColumn('estado', function($pedidos){
                        if ($pedidos->estado == 'P') {
                            return 'Pendiente';
                        } elseif ($pedidos->estado == 'F') {
                            return 'Facturado';
                        } elseif ($pedidos->estado == 'C') {
                            return 'Cancelado';
                        } elseif ($pedidos->estado == 'V') {
                            return 'Vencido';
                        }
                    })
                ->addColumn('action', function($pedidos){
                    return '<a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-sm" title="Ver Pedido" disabled><i class="fa fa-eye"></i></a> ' .'<a data-toggle="tooltip" data-placement="top" title="Editar Pedido" class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i></a> ' .
                           '<a data-toggle="tooltip" data-placement="top" title="Eliminar Pedido" class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i></a>';
                })->make(true);
            }
        }
    }
}
