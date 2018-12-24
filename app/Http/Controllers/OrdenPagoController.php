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
        //
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
                            return $compras->getImporte();
                        })
                        ->addColumn('action', function($compras){
                            $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $compras->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                            $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                            $puede_eliminar = '<a onclick="deleteData('. $compras->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                            $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                                
                            if ($compras->estado == 'P') {
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
                            return $compras->getImporte();
                        })
                        ->addColumn('action', function($compras){
                            $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                            $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                            $puede_eliminar = '<a onclick="deleteData('. $compras->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                            $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
 
                            if ($compras->estado == 'P') {
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
                            return $compras->getImporte();
                        })
                        ->addColumn('action', function($compras){
                            $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $compras->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                            $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                            if ($compras->estado == 'P') {
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
                            return $compras->getImporte();
                        })
                        ->addColumn('action', function($compras){
                            $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                            $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                            if ($compras->estado == 'P') {
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
                        return $compras->getImporte();
                    })
                    ->addColumn('action', function($compras){
                        $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $compras->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        $puede_eliminar = '<a onclick="deleteData('. $compras->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                        $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                        if ($compras->estado == 'P') {
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
                        return $compras->getImporte();
                    })
                    ->addColumn('action', function($compras){
                        $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        $puede_eliminar = '<a onclick="deleteData('. $compras->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                        $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                        if ($compras->estado == 'P') {
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
                        return $compras->getImporte();
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
                        return $compras->getImporte();
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
