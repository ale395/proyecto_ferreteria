<?php

namespace App\Http\Controllers;

use Validator;
use App\DatosDefault;
use App\FacturaVentaCab;
use App\FacturaVentaDet;
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
        return view('facturaVenta.create', compact('fecha_actual', 'moneda', 'lista_precio', 'cambio'));
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
