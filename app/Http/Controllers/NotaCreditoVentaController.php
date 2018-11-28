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
                        return 'Pendiente';
                    } elseif ($notas->estado == 'A') {
                        return 'Anulada';
                    }
                })
                ->addColumn('action', function($facturas){
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
                        return 'Pendiente';
                    } elseif ($notas->estado == 'A') {
                        return 'Anulada';
                    }
                })
                ->addColumn('action', function($facturas){
                    return '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Nota de Crédito" disabled><i class="fa fa-eye"></i></a> ';
                })->make(true);
        }
    }
}
