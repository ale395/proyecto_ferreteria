<?php

namespace App\Http\Controllers;

use App\Serie;
use App\Empresa;
use App\Proveedor;
use App\DatosDefault;
use App\CuentaProveedor;
use App\ComprasCab;
use App\NotaCreditoComprasCab;
use App\NotaCreditoComprasDet;
use App\ExistenciaArticulo;
use App\Cotizacion;
use App\Moneda;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class NotaCreditoComprasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('notacreditocompra.index');
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
        $moneda = $datos_default->moneda;
        $cotizacion = Cotizacion::where('moneda_id','=', $moneda->id)
        ->orderBy('fecha_cotizacion', 'desc')
        ->first();
        // $cotizacion;
        $proveedores = Proveedor::where('activo', true)->get();
        $monedas = Moneda::all();
        $valor_cambio = $cotizacion->getValorVenta();
        
        //var_dump($valor_cambio);
        
        return view('notacreditocompra.create', compact('fecha_actual', 'moneda', 'valor_cambio', 'proveedores', 'monedas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {

            DB::beginTransaction();

            $sucursal = Auth::user()->empleado->sucursalDefault;
            $usuario = Auth::user();
            $cabecera = new NotaCreditoComprasCab();
            $total = 0;
            $array_pedidos = [];
            if ($request['pedidos_id'] != null) {
                $array_pedidos = explode(",",($request['pedidos_id']));
            }
    
            if (!empty('sucursal')) {
                $request['sucursal_id'] = $sucursal->getId();
            }
    
            if (count($array_pedidos) == 0) {
                return redirect()->back()->withErrors('No puede guardar una nota de crédito sin relacionar a una factura!')->withInput();
            }
    
    
            $proveedor = Proveedor::findOrFail($request['proveedor_id']);
    
            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
                $total = $total + str_replace('.', '', $request['tab_subtotal'][$i]);
                $total_exenta = $total_exenta + str_replace('.', '', $request['tab_exenta'][$i]);
                $total_gravada = $total_gravada + str_replace('.', '', $request['tab_gravada'][$i]);
                $total_iva = $total_iva + str_replace('.', '', $request['tab_iva'][$i]);
            }
    
            foreach ($array_pedidos as $nro_factura) {
                $factura_cab = ComprasCab::findOrFail($nro_factura);
                $total_factura = $factura_cab->getMontoSaldo();
                if ($total_factura < $total) {
                    return redirect()->back()->withErrors('La nota de crédito no puede ser mayor al saldo de la factura! El saldo de la factura es de Gs '.$factura_cab->getMontoSaldoFormat())->withInput();
                }
                $cabecera->setFacturaId($nro_factura);
            }
    
            $cabecera->setTipoNotaCredito($request['tipo_nota_credito']);
            $cabecera->setNroNotaCredito($request['nro_nota_credito']);
            $cabecera->setProveedorId($request['proveedor_id']);
            $cabecera->setSucursalId($request['sucursal_id']);
            $cabecera->setMonedaId($request['moneda_id']);
            $cabecera->setValorCambio($request['valor_cambio']);
            $cabecera->setFechaEmision($request['fecha_emision']);
            $cabecera->setComentario($request['comentario']);
            $cabecera->setMontoTotal($total);
            $cabecera->setMontoTotalExenta($total_exenta);
            $cabecera->setMTotalGravada($total_gravada);
            $cabecera->setMTotalIva($total_iva);
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
                    $existencia->actualizaStock('-', $detalle->getCantidad());
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
                $cuenta_factura = CuentaProveedor::where('tipo_comprobante', 'F')
                    ->where('comprobante_id', $nro_factura)
                    ->where('proveedor_id', $cabecera->proveedor->getId())->first();
                $cuenta_factura->setMontoSaldo($cuenta_factura->getMontoSaldo() - str_replace('.', '', $cabecera->getMontoTotal()));
                $cuenta_factura->update();
            }
    
            /*Actualiza el numero de comprobante utilizado para la serie*/
            $serie->setNroActual($serie->getNroActual()+1);
            $serie->update();
    
            //Actualizacion de saldo proveedor
            $cuenta = new CuentaProveedor;
            $cuenta->setTipoComprobante('N');
            $cuenta->setComprobanteId($cabecera->getId());
            $cuenta->setProveedorId($cabecera->proveedor->getId());
            $cuenta->setMontoComprobante(str_replace('.', '', str_replace('.', '', $cabecera->getMontoTotal())*-1));
            $cuenta->setMontoSaldo(0);
            $cuenta->save();

            DB::commit();
            
        }
        catch (\Exception $e) {
            //Deshacemos la transaccion
            DB::rollback();

            //volvemos para atras y retornamos un mensaje de error
            //return back()->withErrors('Ha ocurrido un error. Favor verificar')->withInput();
            return back()->withErrors( $e->getMessage() .' - '.$e->getFile(). ' - '.$e->getLine() )->withInput();
            //return back()->withErrors( $e->getTraceAsString() )->withInput();

        }
        
 
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
        $ncre_cab = NotaCreditoComprasCab::findOrFail($id);
        return view('notacreditocompra.show', compact('ncre_cab'));
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
             //en vez de editar, eliminamos en seco si algo se cargó mal
             $permiso_editar = Auth::user()->can('compra.destroy');
             $permiso_eliminar = Auth::user()->can('compra.destroy');
             $permiso_ver = Auth::user()->can('compra.show');
             $sucursal_actual = Auth::user()->empleado->sucursales->first();
             $notas = NotaCreditoComprasCab::where('sucursal_id',$sucursal_actual->getId())->get();
     
             if ($permiso_editar) {
                 if($permiso_eliminar){
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
                            ->addColumn('proveedor', function($notas){
                                return $notas->proveedor->getNombreIndex();
                            })
                            ->addColumn('moneda', function($notas){
                                return $notas->moneda->getDescripcion();
                            })
                            ->addColumn('monto_total', function($notas){
                                return $notas->getMontoTotal();
                            })
                             ->addColumn('action', function($notas){
                                 $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $notas->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                                 $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $notas->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                                 $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                                 $puede_eliminar = '<a onclick="deleteData('. $notas->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                                 $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                                     
                                 if ($notas->estado == 'P') {
                                     return $puede_ver.$puede_editar.$puede_eliminar;
                                 } else {
                                     return $puede_ver.$no_puede_editar.$no_puede_eliminar;
                                 }
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
                            ->addColumn('proveedor', function($notas){
                                return $notas->proveedor->getNombreIndex();
                            })
                            ->addColumn('moneda', function($notas){
                                return $notas->moneda->getDescripcion();
                            })
                            ->addColumn('monto_total', function($notas){
                                return $notas->getMontoTotal();
                            })
                             ->addColumn('action', function($notas){
                                 $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                                 $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $notas->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                                 $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                                 $puede_eliminar = '<a onclick="deleteData('. $notas->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                                 $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
      
                                 if ($notas->estado == 'P') {
                                     return $no_puede_ver.$puede_editar.$puede_eliminar;
                                 } else {
                                     return $no_puede_ver.$no_puede_editar.$no_puede_eliminar;
                                 }
                             })->make(true);
                     }
                 } else {
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
                            ->addColumn('proveedor', function($notas){
                                return $notas->proveedor->getNombreIndex();
                            })
                            ->addColumn('moneda', function($notas){
                                return $notas->moneda->getDescripcion();
                            })
                            ->addColumn('monto_total', function($notas){
                                return $notas->getMontoTotal();
                            })
                             ->addColumn('action', function($notas){
                                 $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $notas->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                                 $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $notas->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                                 $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                                 $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
     
                                 if ($notas->estado == 'P') {
                                     return $puede_ver.$puede_editar.$no_puede_eliminar;
                                 } else {
                                     return $puede_ver.$no_puede_editar.$no_puede_eliminar;
                                 }
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
                            ->addColumn('proveedor', function($notas){
                                return $notas->proveedor->getNombreIndex();
                            })
                            ->addColumn('moneda', function($notas){
                                return $notas->moneda->getDescripcion();
                            })
                            ->addColumn('monto_total', function($notas){
                                return $notas->getMontoTotal();
                            })
                             ->addColumn('action', function($notas){
                                 $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                                 $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $notas->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                                 $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                                 $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
     
                                 if ($notas->estado == 'P') {
                                     return $no_puede_ver.$puede_editar.$no_puede_eliminar;
                                 } else {
                                     return $no_puede_ver.$no_puede_editar.$no_puede_eliminar;
                                 }
                             })->make(true);
                     }
                 }
             } elseif ($permiso_eliminar){
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
                        ->addColumn('proveedor', function($notas){
                            return $notas->proveedor->getNombreIndex();
                        })
                        ->addColumn('moneda', function($notas){
                            return $notas->moneda->getDescripcion();
                        })
                        ->addColumn('monto_total', function($notas){
                            return $notas->getMontoTotal();
                        })
                         ->addColumn('action', function($notas){
                             $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $notas->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                             $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $notas->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                             $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                             $puede_eliminar = '<a onclick="deleteData('. $notas->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                             $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
     
                             if ($notas->estado == 'P') {
                                 return $puede_ver.$puede_editar.$puede_eliminar;
                             } else {
                                 return $puede_ver.$no_puede_editar.$no_puede_eliminar;
                             }
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
                        ->addColumn('proveedor', function($notas){
                            return $notas->proveedor->getNombreIndex();
                        })
                        ->addColumn('moneda', function($notas){
                            return $notas->moneda->getDescripcion();
                        })
                        ->addColumn('monto_total', function($notas){
                            return $notas->getMontoTotal();
                        })
                         ->addColumn('action', function($notas){
                             $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                             $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $notas->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                             $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                             $puede_eliminar = '<a onclick="deleteData('. $notas->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                             $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
     
                             if ($notas->estado == 'P') {
                                 return $no_puede_ver.$puede_editar.$puede_eliminar;
                             } else {
                                 return $no_puede_ver.$no_puede_editar.$no_puede_eliminar;
                             }
                         })->make(true);
                 }
             } else {
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
                        ->addColumn('proveedor', function($notas){
                            return $notas->proveedor->getNombreIndex();
                        })
                        ->addColumn('moneda', function($notas){
                            return $notas->moneda->getDescripcion();
                        })
                        ->addColumn('monto_total', function($notas){
                            return $notas->getMontoTotal();
                        })
                         ->addColumn('action', function($notas){
                             $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $notas->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                             $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                             $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                             
                             return $puede_ver.$no_puede_editar.$no_puede_eliminar;
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
                        ->addColumn('proveedor', function($notas){
                            return $notas->proveedor->getNombreIndex();
                        })
                        ->addColumn('moneda', function($notas){
                            return $notas->moneda->getDescripcion();
                        })
                        ->addColumn('monto_total', function($notas){
                            return $notas->getMontoTotal();
                        })
                         ->addColumn('action', function($notas){
                             $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                             $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                             $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
     
                             return $no_puede_ver.$no_puede_editar.$no_puede_eliminar;
                         })->make(true);
                 }
             }
    }
}
