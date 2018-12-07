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
use App\ExistenciaArticulo;
use App\CuentaProveedor;
use DB;
use Response;
use Illuminate\Support\Collections;
use PhpParser\Node\Stmt\Else_;


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
        // $cotizacion;
        $proveedores = Proveedor::where('activo', true)->get();
        $monedas = Moneda::all();
        $valor_cambio = $cotizacion->getValorVenta();
        
        //var_dump($valor_cambio);
        
        return view('compra.create', compact('fecha_actual', 'moneda', 'valor_cambio', 'proveedores', 'monedas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompraRequest $request)
    {

        try {

            DB::beginTransaction();

            $sucursal = Auth::user()->empleado->sucursalDefault;
            $usuario = Auth::user();
            $cabecera = new ComprasCab();
            $total = 0;
            $total_exenta = 0;
            $total_gravada = 0;
            $total_iva = 0;

            $modalidad_pago = $request['tipo_factura'];
            
            if (!empty('sucursal')) {
                $request['sucursal_id'] = $sucursal->getId();
            }
            
            $proveedor = Proveedor::findOrFail($request['proveedor_id']);
            
            /*if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }*/

   
            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
                
                //var_dump(str_replace('.', '', $request['tab_subtotal'][$i]));

                $total = $total + str_replace('.', '', $request['tab_subtotal'][$i]);
                $total_exenta = $total_exenta + str_replace('.', '', $request['tab_exenta'][$i]);
                $total_gravada = $total_gravada + str_replace('.', '', $request['tab_gravada'][$i]);
                $total_iva = $total_iva + str_replace('.', '', $request['tab_iva'][$i]);
            }
    
            $cabecera->setTipoFactura($request['tipo_factura']);
            $cabecera->setNroFactura($request['nro_factura']);
            $cabecera->setProveedorId($request['proveedor_id']);
            $cabecera->setTimbrado($request['timbrado']);
            $cabecera->setSucursalId($request['sucursal_id']);
            $cabecera->setMonedaId($request['moneda_id']);
            $cabecera->setValorCambio($request['valor_cambio']);
            $cabecera->setFechaEmision($request['fecha_emision']);
            $cabecera->setComentario($request['comentario']);
            $cabecera->setMontoTotal($total);
            $cabecera->setTotalExenta($total_exenta);
            $cabecera->setTotalGravada($total_gravada);
            $cabecera->setTotalIva($total_iva);
            $cabecera->setUsuarioId($usuario->id);
    
            $cabecera->save();
    
            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){

                //para traer despues el costo promedio
                $articulo = Articulo::findOrFail($request['tab_articulo_id'][$i]);

                //$costo_pp = str_replace('.', '', $articulo->getCostoPromedio());

                $detalle = new ComprasDet();

                $detalle->setCompraCabeceraId($cabecera->getId());
                $detalle->setArticuloId($request['tab_articulo_id'][$i]);
                $detalle->setCantidad(str_replace(',', '.', str_replace('.', '', $request['tab_cantidad'][$i])));
                $detalle->setCostoUnitario(str_replace('.', '', $request['tab_costo_unitario'][$i]));
                $detalle->setCostoPromedio(str_replace('.', '', $articulo->getCostoPromedio()));
                $detalle->setPorcentajeDescuento(str_replace('.', '', $request['tab_porcentaje_descuento'][$i]));
                $detalle->setMontoDescuento(str_replace('.', '', $request['tab_monto_descuento'][$i]));
                $detalle->setPorcentajeIva(round(str_replace('.', ',', $request['tab_porcentaje_iva'][$i])), 0);
                $detalle->setMontoExenta(str_replace('.', '', $request['tab_exenta'][$i]));
                $detalle->setMontoGravada(str_replace('.', '', $request['tab_gravada'][$i]));
                $detalle->setMontoIva(str_replace('.', '', $request['tab_iva'][$i]));
                $detalle->setMontoTotal(str_replace('.', '', $request['tab_subtotal'][$i]));

                //var_dump($detalle);

                $detalle->save();
    
                //controlamos existencia
                if ($detalle->articulo->getControlExistencia() == true) {
                    //Actualizacion de existencia
                    $existencia = ExistenciaArticulo::where('articulo_id', $detalle->articulo->getId())
                        ->where('sucursal_id', $sucursal->getId())->first();

                    //si aún no existe el artícuo en la tabla de existencia, insertamos un nuevo registro 
                    if (!empty($existencia)){
                        $existencia->actualizaStock('+', $detalle->getCantidad());
                        $existencia->update();                        
                    } else {
                        $existencia_nuevo = new ExistenciaArticulo();

                        $existencia_nuevo->setArticuloId($detalle->articulo->getId());
                        $existencia_nuevo->setSucursalId($sucursal->getId());
                        $existencia_nuevo->setCantidad($detalle->getCantidad());
                        $existencia_nuevo->setFechaUltimoInventario($request['fecha_emision']);

                        $existencia_nuevo->save();  
                    }   

                }

                //----------------para el costo promedio-----------------------------------
                $id =  $request['tab_articulo_id'][$i]; 

                $r_total_costos = DB::table('compras_det as o')
                ->select( DB::raw("sum(o.costo_unitario*o.cantidad) as costo_unitario"))
                ->where('o.articulo_id','=',$id)->first();

                $total_costos = $r_total_costos->costo_unitario;
                
                //var_dump($total_costos);

                $r_total_cantidades = DB::table('compras_det as o')
                ->select( DB::raw("sum(o.cantidad) as cantidad"))
                ->where('o.articulo_id','=',$id)->first();

                $total_cantidades = $r_total_cantidades->cantidad;

                //var_dump($total_cantidades);

                $articulo_costo = Articulo::findOrFail($id);

                //si aún no hay compras, el costo promedio va a a ser igual al último costo
                if (!empty( $total_costos) && !empty( $total_cantidades)) {
                    $articulo_costo->costo_promedio = ($total_costos / $total_cantidades);
                } else {
                    $articulo_costo->costo_promedio = str_replace('.', '', $request['tab_costo_unitario'][$i]);
                }

                $articulo_costo->ultimo_costo = str_replace('.', '', $request['tab_costo_unitario'][$i]);

                $articulo_costo->update();
                //----------------para el costo promedio-----------------------------------

                //$detale_costo = ComprasDet::where('articulo_id', $request['tab_articulo_id'][$i])->get();
            }
  
            if ($modalidad_pago != 'CON'){
                //Actualizacion de saldo proveedor
                $cuenta = new CuentaProveedor;
                $cuenta->setTipoComprobante('F');
                $cuenta->setComprobanteId($cabecera->getId());
                $cuenta->setMontoComprobante(str_replace('.', '', $cabecera->getMontoTotal()));
                $cuenta->setMontoSaldo(str_replace('.', '', $cabecera->getMontoTotal()));
                $cuenta->save();
            
            } 

            DB::commit();
            
        }
        catch (\Exception $e) {
            //$error = $e->getMessage();
            //Deshacemos la transaccion
            DB::rollback();
            //volvemos para atras y retornamos un mensaje de error
            //return back()->withErrors('Ha ocurrido un error. Favor verificar')->withInput();
            return back()->withErrors( $e->getMessage() .' - '.$e->getFile(). ' - '.$e->getLine() )->withInput();
            //return back()->withErrors( $e->getTraceAsString() )->withInput();

        }

        return redirect(route('compra.create'))->with('status', 'Datos guardados correctamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $factura_cab = ComprasCab::findOrFail($id);
        return view('compra.show', compact('factura_cab'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $factura_cab = ComprasCab::findOrFail($id);
        $proveedores = Proveedor::where('activo', true)->get();
        $monedas = Moneda::all();

        return view('compra.show', compact('factura_cab', 'proveedores', 'monedas'));
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
        //en vez de editar, eliminamos en seco si algo se cargó mal
        $permiso_editar = Auth::user()->can('compra.destroy');
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
                    ->addColumn('proveedor', function($compras){
                        return $compras->proveedor->getNombreIndex();
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
                    ->addColumn('proveedor', function($compras){
                        return $compras->proveedor->getNombreIndex();
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
                    ->addColumn('proveedor', function($compras){
                        return $compras->proveedor->getNombreIndex();
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
