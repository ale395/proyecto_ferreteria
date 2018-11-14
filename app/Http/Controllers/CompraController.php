<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\CompraRequest;
use App\ComprasCab;
use App\ComprasDet;
use App\Proveedor;
use App\Moneda;
use App\Articulo;
use App\DatosDefault;
use App\Impuesto;
use App\Cotizacion;
use DB;
use Response;
use Illuminate\Support\Collections;


class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('compra.index');            
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
        
        $cambio = $cotizacion->getValorVenta;
        return view('compra.create', compact('fecha_actual', 'moneda', 'cambio'));
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

    public function apiCompras(){
        $permiso_editar = Auth::user()->can('compra.edit');
        //$permiso_eliminar = Auth::user()->can('compra.destroy');
        $permiso_ver = Auth::user()->can('compra.show');
        $sucursal_actual = Auth::user()->empleado->sucursales->first();
        $compras = ComprasCab::where('sucursal_id',$sucursal_actual->getId())->get();

        if ($permiso_editar) {
            if ($permiso_ver) {
                return Datatables::of($compras)
                    ->addColumn('tipo_factura', function($compras){
                        return $compras->getTipoFacturaIndex();
                    })
                    ->addColumn('nro_factura', function($compras){
                        return $compras->getNroFacturaIndex();
                    })
                    ->addColumn('fecha', function($compras){
                        return $compras->getFechaEmision();
                    })
                    ->addColumn('proveedor', function($compras){
                        return $compras->provedor->getNombreIndex();
                    })
                    ->addColumn('moneda', function($compras){
                        return $compras->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($compras){
                        return $compras->getMontoTotal();
                    })
                    ->addColumn('action', function($compras){
                        $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $compras->id .')" class="btn btn-primary btn-sm" title="Ver Factura"><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Factura"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        if ($compras->estado == 'P') {
                            return $puede_ver.$puede_editar;
                        } else {
                            return $puede_ver.$no_puede_editar;
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
                    ->addColumn('cliente', function($compras){
                        return $compras->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($compras){
                        return $compras->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($compras){
                        return $compras->getMontoTotal();
                    })
                    ->addColumn('action', function($compras){
                        $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Factura" disabled><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Factura"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        if ($compras->estado == 'P') {
                            return $no_puede_ver.$puede_editar;
                        } else {
                            return $no_puede_ver.$no_puede_editar;
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
                    ->addColumn('cliente', function($compras){
                        return $compras->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($compras){
                        return $compras->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($compras){
                        return $compras->getMontoTotal();
                    })
                    ->addColumn('action', function($compras){
                        $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $compras->id .')" class="btn btn-primary btn-sm" title="Ver Factura"><i class="fa fa-eye"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        return $puede_ver.$no_puede_editar;
                    })->make(true);
            } else {
                return Datatables::of($compras)
                    ->addColumn('tipo_factura', function($compras){
                        return $compras->getTipoFacturaIndex();
                    })
                    ->addColumn('fecha', function($compras){
                        return $compras->getFechaEmision();
                    })
                    ->addColumn('cliente', function($compras){
                        return $compras->cliente->getNombreIndex();
                    })
                    ->addColumn('moneda', function($compras){
                        return $compras->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($compras){
                        return $compras->getMontoTotal();
                    })
                    ->addColumn('action', function($compras){
                        $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Factura" disabled><i class="fa fa-eye"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        return $no_puede_ver.$no_puede_editar;
                    })->make(true);
            }
        }
    }
}
