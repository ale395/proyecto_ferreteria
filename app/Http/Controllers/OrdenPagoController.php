<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\OrdenPagoRequest;
use App\OrdenPago;
use App\OrdenPagoFacturas;
use App\OrdenPagoCheques;
use App\Proveedor;
use App\Moneda;
use App\Articulo;
use App\DatosDefault;
use App\Impuesto;
use App\Cotizacion;
use App\CuentaProveedor;
use App\ComprasCab;
use Barryvdh\DomPDF\Facade as PDF;
use DB;
use Response;
use Illuminate\Support\Collections;
class OrdenPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ordenpago.index');
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
        
        //$nro_orden = DB::table('orden_compras_cab')->select(DB::raw('coalesce(nro_orden),0) + 1 as nro_orden'))->get();
        //$nro_orden = DB::table('orden_compras_cab')->orderBy('nro_orden', 'desc')->first();    

        $nro_orden_compra = OrdenPago::max('nro_orden');

        if($nro_orden_compra) {
            $nro_orden = $nro_orden_compra + 1; 
        } else {
            $nro_orden = 1;       
        }
        

        return view('ordenpago.create',compact('fecha_actual', 'nro_orden', 'moneda', 'valor_cambio', 'monedas', 'proveedores'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrdenPagoRequest $request)
    {
        try {

            DB::beginTransaction();

            $sucursal = Auth::user()->empleado->sucursalDefault;
            $usuario = Auth::user();
            $cabecera = new OrdenPago();
            $total = 0;
            $total_che = 0;

            $valor_cambio = $request['valor_cambio'];
            var_dump($valor_cambio);
            $valor_cambio = number_format($valor_cambio, 2, '.', '');
            
            if (!empty('sucursal')) {
                $request['sucursal_id'] = $sucursal->getId();
            }
            
            $proveedor = Proveedor::findOrFail($request['proveedor_id']);
            
            for ($i=0; $i < collect($request['tab_compra_id'])->count(); $i++){
                
                $total = $total + str_replace('.', '', $request['tab_importe_afectado'][$i]);
            }

            for ($i=0; $i < collect($request['tab_banco_id'])->count(); $i++){
                
                $total_che = $total_che + str_replace('.', '', $request['tab_importe_che'][$i]);
            }
            
            if (!($total == $total_che)) {
                return redirect()->back()->withErrors('Los importes afectados deben coincidir con el importe de los pagos!')->withInput();
            }
    
            //pasamos los parámetros del request
            $cabecera->nro_orden = $request['nro_orden'];
            $cabecera->proveedor_id = $request['proveedor_id'];
            $cabecera->fecha_emision = $request['fecha_emision'];   
            $cabecera->sucursal_id = $request['sucursal_id'];     
            $cabecera->moneda_id = $request['moneda_id'];
            $cabecera->valor_cambio = $request['valor_cambio'];
            $cabecera->monto_total = $total;
            $cabecera->estado = 'A';
            $cabecera->usuario_id = $usuario->id;

            //guardamos
            $cabecera->save();
    
            $cabecera->save();
    
            for ($i=0; $i < collect($request['tab_compra_id'])->count(); $i++){

                //para traer despues el costo promedio
                $compra = ComprasCab::findOrFail($request['tab_compra_id'][$i]);

                $importe_afectado = str_replace('.', '', $request['tab_importe_afectado'][$i]);

                $detalle = new OrdenPagoFacturas();

                $detalle->setOrdenPagoId($cabecera->getId());
                $detalle->setCompraId($request['tab_compra_id'][$i]);
                $detalle->setImporte(str_replace('.', '', $importe_afectado));

                //var_dump($detalle);

                $detalle->save();

                //Actualiza el saldo de la factura relacionada a la orden de pago
                $cuenta_factura = CuentaProveedor::where('tipo_comprobante', 'F')
                ->where('comprobante_id', $compra->getId())->first();
                $cuenta_factura->setMontoSaldo($cuenta_factura->getMontoSaldo() - str_replace('.', '', $importe_afectado));
                $cuenta_factura->update();

            }

            //Actualizacion de saldo proveedor
            $cuenta = new CuentaProveedor;
            $cuenta->setTipoComprobante('P');
            $cuenta->setComprobanteId($cabecera->getId());
            $cuenta->setMontoComprobante($total*-1);
            $cuenta->setMontoSaldo(0);
            $cuenta->save();

            for ($i=0; $i < collect($request['tab_banco_id'])->count(); $i++){

                $detalle_che = new OrdenPagoCheques();

                $detalle_che->setOrdenPagoId($cabecera->getId());
                $detalle_che->setBancoId($request['tab_compra_id'][$i]);
                $detalle_che->setMonedaId($request['tab_moneda_che_id'][$i]);
                $detalle_che->setValorCambio(str_replace('.', '', $request['valor_cambio']));
                $detalle_che->setNroCuenta($request['tab_cuenta'][$i]);
                $detalle_che->setLibrador($request['tab_librador'][$i]);
                $detalle_che->setFechaEmision($request['tab_fecha_emi'][$i]);
                $detalle_che->setFechaVencimiento($request['tab_fecha_venc'][$i]);
                $detalle_che->setImporte(str_replace('.', '', $request['tab_importe_che'][$i]));

                //var_dump($detalle);

                $detalle_che->save();

            }

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

        return redirect(route('ordenpago.create'))->with('status', 'Datos guardados correctamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //en el show directo tiramos el reporte para la impresión.
        $orden_pago = DB::table('orden_pago as o')
        ->join('proveedores as p', 'p.id','=', 'o.proveedor_id')
        ->join('monedas as m', 'm.id','=', 'o.moneda_id')
        ->select('o.id', 'o.nro_orden', 
        DB::raw("to_char(o.fecha_emision, 'DD/MM/YYYY') as fecha_emision"), 'o.proveedor_id',
        DB::raw("CONCAT(p.codigo, ' ', p.razon_social) as proveedor"),
        DB::raw("case when estado = 'A' THEN 'ACEPTADO' ELSE 'CANCELADO' END AS estado"),
        'o.moneda_id','m.codigo', 'm.descripcion as moneda', 'o.valor_cambio', 'o.monto_total')
        ->where('o.id','=',$id)->first();

        $orden_pago_facturas = DB::table('orden_pago_facturas as od')
        ->join('compras_cab as a', 'a.id','=', 'od.compras_id')
        ->select('od.orden_pago_id',
         DB::raw("CONCAT(a.nro_factura, ' ', to_char(a.fecha_emision, 'DD/MM/YYYY')) as compra"), 
        'a.monto_total as importe_factura', 'od.importe_afectado')
        ->where('od.orden_pago_id','=',$id)
        ->get();

        $orden_pago_cheques = DB::table('orden_pago_cheques as od')
        ->join('bancos as a', 'a.id','=', 'od.banco_id')
        ->join('monedas as m', 'm.id','=', 'od.moneda_id')
        ->select('od.orden_pago_id', DB::raw("CONCAT(a.codigo, ' - ', a.nombre) as banco"), 
        DB::raw("to_char(od.fecha_emision, 'DD/MM/YYYY') as fecha_emision"), 
        DB::raw("to_char(od.fecha_vencimiento, 'DD/MM/YYYY') as fecha_vencimiento"),
        'od.moneda_id','m.codigo', 'm.descripcion as moneda','od.librador', 'od.nro_cuenta','od.importe')
        ->where('od.orden_pago_id','=',$id)
        ->get();

        $pdf = PDF::loadView('ordenpago.show', compact('orden_pago', 'orden_pago_facturas', 'orden_pago_cheques'));

        $nombre_archivo = "orden_compra_".$orden_pago->nro_orden."_".str_replace('/', '', $orden_pago->fecha_emision).".pdf";

        return $pdf->stream($nombre_archivo, array('Attachment'=>0));

        // return view('ordenpago.show',compact('orden_pago', 'orden_pago_facturas'));
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
    public function update(OrdenPagoRequest $request, $id)
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
        try {

            DB::beginTransaction();

            $cabecera = OrdenPago::findOrFail($id);

            
            $modalidad_pago = $cabecera->getTipoFactura();

            if (!empty($cuenta ) && $modalidad_pago != 'CO') {                
                $cuenta->delete();
            }
            
            foreach ($cabecera->ordenpagofacturas() as $detalle) {

                $id_comprobante = $detalle->compra()->getId();
                $importe_afectado = str_replace('.', '', $detalle->getImporte());

                $cuenta = CuentaProveedor::where('comprobante_id', $id_comprobante)
                            ->where('tipo_comprobante', 'F')->firstOrFail();
                $cuenta->setMontoSaldo($cuenta_factura->getMontoSaldo() + str_replace('.', '', $importe_afectado));
                $cuenta->update();

            }

            //Buscamos el registro en el saldo de proveedores
            $cuenta_op = CuentaProveedor::where('comprobante_id', $id_comprobante)
            ->where('tipo_comprobante', 'P')->firstOrFail();

            $cuenta_op->delete();
            //borramos facturas afectadas
            $cabecera->ordenpagofacturas()->delete();
            //borramos los cheques
            $cabecera->ordenpagocheques()->delete();
            //borramos la cabecera
            $cabecera->delete();

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
    }

    public function apiOrdenPago(){
        //en vez de editar, eliminamos en seco si algo se cargó mal
        $permiso_editar = Auth::user()->can('ordenpago.destroy');
        $permiso_eliminar = Auth::user()->can('ordenpago.destroy');
        $permiso_ver = Auth::user()->can('ordenpago.show');
        $sucursal_actual = Auth::user()->empleado->sucursales->first();
        $compras = OrdenPago::where('sucursal_id',$sucursal_actual->getId())->get();

        if ($permiso_editar) {
            if($permiso_eliminar){
                if ($permiso_ver) {
                    return Datatables::of($compras)
                        ->addColumn('nro_oroden', function($compras){
                            return $compras->getNroOrden();
                        })
                        ->addColumn('fecha', function($compras){
                            return $compras->getFechaEmision();
                        })
                        ->addColumn('proveedor', function($compras){
                            return $compras->proveedor->getNombreIndex();
                        })
                        ->addColumn('moneda', function($compras){
                            return $compras->moneda->getDescripcion();
                        })
                        ->addColumn('importe', function($compras){
                            return $compras->getMontoTotal();
                        })
                        ->addColumn('action', function($compras){
                            $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $compras->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                            $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                            $puede_eliminar = '<a onclick="deleteData('. $compras->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                            $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                                
                            if ($compras->estado == 'A') {
                                return $puede_ver.$puede_editar.$puede_eliminar;
                            } else {
                                return $puede_ver.$no_puede_editar.$no_puede_eliminar;
                            }
                        })->make(true);
                } else {
                    return Datatables::of($compras)
                        ->addColumn('tipo_factura', function($compras){
                            return $compras->getTipoFacturaIndex();
                        })
                        ->addColumn('fecha', function($compras){
                            return $compras->getFechaEmision();
                        })
                        ->addColumn('proveedor', function($compras){
                            return $compras->proveedor->getNombreIndex();
                        })
                        ->addColumn('moneda', function($compras){
                            return $compras->moneda->getDescripcion();
                        })
                        ->addColumn('importe', function($compras){
                            return $compras->getMontoTotal();
                        })
                        ->addColumn('action', function($compras){
                            $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                            $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                            $puede_eliminar = '<a onclick="deleteData('. $compras->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                            $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
 
                            if ($compras->estado == 'A') {
                                return $no_puede_ver.$puede_editar.$puede_eliminar;
                            } else {
                                return $no_puede_ver.$no_puede_editar.$no_puede_eliminar;
                            }
                        })->make(true);
                }
            } else {
                if ($permiso_ver) {
                    return Datatables::of($compras)
                        ->addColumn('tipo_factura', function($compras){
                            return $compras->getTipoFacturaIndex();
                        })
                        ->addColumn('nro_factura', function($compras){
                            return $compras->getNroFactura();
                        })
                        ->addColumn('fecha', function($compras){
                            return $compras->getFechaEmision();
                        })
                        ->addColumn('proveedor', function($compras){
                            return $compras->proveedor->getNombreIndex();
                        })
                        ->addColumn('moneda', function($compras){
                            return $compras->moneda->getDescripcion();
                        })
                        ->addColumn('importe', function($compras){
                            return $compras->getMontoTotal();
                        })
                        ->addColumn('action', function($compras){
                            $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $compras->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                            $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                            if ($compras->estado == 'A') {
                                return $puede_ver.$puede_editar.$no_puede_eliminar;
                            } else {
                                return $puede_ver.$no_puede_editar.$no_puede_eliminar;
                            }
                        })->make(true);
                } else {
                    return Datatables::of($compras)
                        ->addColumn('tipo_factura', function($compras){
                            return $compras->getTipoFacturaIndex();
                        })
                        ->addColumn('fecha', function($compras){
                            return $compras->getFechaEmision();
                        })
                        ->addColumn('proveedor', function($compras){
                            return $compras->proveedor->getNombreIndex();
                        })
                        ->addColumn('moneda', function($compras){
                            return $compras->moneda->getDescripcion();
                        })
                        ->addColumn('importe', function($compras){
                            return $compras->getMontoTotal();
                        })
                        ->addColumn('action', function($compras){
                            $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                            $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                            if ($compras->estado == 'A') {
                                return $no_puede_ver.$puede_editar.$no_puede_eliminar;
                            } else {
                                return $no_puede_ver.$no_puede_editar.$no_puede_eliminar;
                            }
                        })->make(true);
                }
            }
        } elseif ($permiso_eliminar){
            if ($permiso_ver) {
                return Datatables::of($compras)
                    ->addColumn('tipo_factura', function($compras){
                        return $compras->getTipoFacturaIndex();
                    })
                    ->addColumn('nro_factura', function($compras){
                        return $compras->getNroFactura();
                    })
                    ->addColumn('fecha', function($compras){
                        return $compras->getFechaEmision();
                    })
                    ->addColumn('proveedor', function($compras){
                        return $compras->proveedor->getNombreIndex();
                    })
                    ->addColumn('moneda', function($compras){
                        return $compras->moneda->getDescripcion();
                    })
                    ->addColumn('importe', function($compras){
                        return $compras->getMontoTotal();
                    })
                    ->addColumn('action', function($compras){
                        $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $compras->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        $puede_eliminar = '<a onclick="deleteData('. $compras->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                        $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                        if ($compras->estado == 'A') {
                            return $puede_ver.$puede_editar.$puede_eliminar;
                        } else {
                            return $puede_ver.$no_puede_editar.$no_puede_eliminar;
                        }
                    })->make(true);
            } else {
                return Datatables::of($compras)
                    ->addColumn('tipo_factura', function($compras){
                        return $compras->getTipoFacturaIndex();
                    })
                    ->addColumn('fecha', function($compras){
                        return $compras->getFechaEmision();
                    })
                    ->addColumn('proveedor', function($compras){
                        return $compras->proveedor->getNombreIndex();
                    })
                    ->addColumn('moneda', function($compras){
                        return $compras->moneda->getDescripcion();
                    })
                    ->addColumn('importe', function($compras){
                        return $compras->getMontoTotal();
                    })
                    ->addColumn('action', function($compras){
                        $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        $puede_eliminar = '<a onclick="deleteData('. $compras->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                        $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                        if ($compras->estado == 'A') {
                            return $no_puede_ver.$puede_editar.$puede_eliminar;
                        } else {
                            return $no_puede_ver.$no_puede_editar.$no_puede_eliminar;
                        }
                    })->make(true);
            }
        } else {
            if ($permiso_ver) {
                return Datatables::of($compras)
                    ->addColumn('tipo_factura', function($compras){
                        return $compras->getTipoFacturaIndex();
                    })
                    ->addColumn('fecha', function($compras){
                        return $compras->getFechaEmision();
                    })
                    ->addColumn('proveedor', function($compras){
                        return $compras->proveedor->getNombreIndex();
                    })
                    ->addColumn('moneda', function($compras){
                        return $compras->moneda->getDescripcion();
                    })
                    ->addColumn('importe', function($compras){
                        return $compras->getMontoTotal();
                    })
                    ->addColumn('action', function($compras){
                        $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $compras->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                        
                        return $puede_ver.$no_puede_editar.$no_puede_eliminar;
                    })->make(true);
            } else {
                return Datatables::of($compras)
                    ->addColumn('tipo_factura', function($compras){
                        return $compras->getTipoFacturaIndex();
                    })
                    ->addColumn('fecha', function($compras){
                        return $compras->getFechaEmision();
                    })
                    ->addColumn('proveedor', function($compras){
                        return $compras->proveedor->getNombreIndex();
                    })
                    ->addColumn('moneda', function($compras){
                        return $compras->moneda->getDescripcion();
                    })
                    ->addColumn('importe', function($compras){
                        return $compras->getMontoTotal();
                    })
                    ->addColumn('action', function($compras){
                        $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                        return $no_puede_ver.$no_puede_editar.$no_puede_eliminar;
                    })->make(true);
            }
        }
    }
}
