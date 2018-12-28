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
use App\CuentaProveedor;
use App\NotaCreditoComprasCab;
use App\OrdenPagoFacturas;
use App\MovimientoArticulo;
use App\ExistenciaArticulo;
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

            $valor_cambio = $request['valor_cambio'];
            var_dump($valor_cambio);
            $valor_cambio = number_format($valor_cambio, 2, '.', '');

            $array_pedidos = [];
            if ($request['pedidos_id'] != null) {
                $array_pedidos = explode(",",($request['pedidos_id']));
            }
            
            if (!empty('sucursal')) {
                $request['sucursal_id'] = $sucursal->getId();
            }
            
            $proveedor = Proveedor::findOrFail($request['proveedor_id']);
            
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
            $cabecera->setFechaVigenciaTimbrado($request['fecha_vigencia_timbrado']);
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

                //Actualizacion de la captura de movimiento de articulos
                $movimiento = new MovimientoArticulo;
                $movimiento->setFecha($request['fecha_emision']);   
                $movimiento->setTipoMovimiento('C');
                $movimiento->setMovimientoId($cabecera->getId());
                $movimiento->setSucursalId($cabecera->sucursal->getId());
                $movimiento->setArticuloId($detalle->articulo->getId());      
                $movimiento->setCantidad($detalle->getCantidad());             

                $movimiento->save();

            }

            if (count($array_pedidos) > 0) {
                foreach ($array_pedidos as $nro_pedido) {
                    $pedido_cab = OrdenCompraCab::findOrFail($nro_pedido);
                    $pedido_cab->setEstado('F');
                    $pedido_cab->update();
    
                    $cabecera_orden_compra = OrdenCompraCab::findOrFail($nro_pedido);
                    $cabecera_orden_compra->setOrdenCompraId($pedido_cab->getId());
                    $cabecera_orden_compra->update();

                    $cabecera->setOrdenCompraId($pedido_cab->getId());
                    $cabecera->update();

                    //$pedido_factura = new PedidoFactura;
                    //$pedido_factura->setPedidoId($pedido_cab->getId());
                    //$pedido_factura->setFacturaId($cabecera->getId());
                    //$pedido_factura->save();
                }
            }
            // a pedido de Duré, las compras contado también deben figurar en el extracto   
            // if ($modalidad_pago != 'CO'){
            
            // Actualizacion de saldo proveedor
            $cuenta = new CuentaProveedor;
            $cuenta->setTipoComprobante('F');
            $cuenta->setComprobanteId($cabecera->getId());
            $cuenta->setMontoComprobante(str_replace('.', '', $cabecera->getMontoTotal()));
            $cuenta->setMontoSaldo(str_replace('.', '', $cabecera->getMontoTotal()));
            $cuenta->save();
            
            // } 

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

        return view('compra.edit', compact('factura_cab', 'proveedores', 'monedas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompraRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            $sucursal = Auth::user()->empleado->sucursalDefault;
            $usuario = Auth::user();
            $cabecera = ComprasCab::findOrFail($id);
            $total = 0;
            $total_exenta = 0;
            $total_gravada = 0;
            $total_iva = 0;

            $modalidad_pago = $request['tipo_factura'];
            
            if (!empty('sucursal')) {
                $request['sucursal_id'] = $sucursal->getId();
            }
            
            $proveedor = Proveedor::findOrFail($request['proveedor_id']);

            /*
            if (!empty($cuenta ) && $modalidad_pago == 'CO') {
                $cuenta = CuentaProveedor::findOrFail($cabecera->getId());
                
                $cuenta->delete();
            }
            */
            /*
            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
                
                //var_dump(str_replace('.', '', $request['tab_subtotal'][$i]));

                $total = $total + str_replace('.', '', $request['tab_subtotal'][$i]);
                $total_exenta = $total_exenta + str_replace('.', '', $request['tab_exenta'][$i]);
                $total_gravada = $total_gravada + str_replace('.', '', $request['tab_gravada'][$i]);
                $total_iva = $total_iva + str_replace('.', '', $request['tab_iva'][$i]);
            }
            */

            //Solamente datos de cabecera, menos los importes y la moneda.
            //si eso está mal, eliminamos y volvemos a cargar.
            $cabecera->setTipoFactura($request['tipo_factura']);
            $cabecera->setNroFactura($request['nro_factura']);
            $cabecera->setProveedorId($request['proveedor_id']);
            $cabecera->setTimbrado($request['timbrado']);
            $cabecera->setSucursalId($request['sucursal_id']);
            //$cabecera->setMonedaId($request['moneda_id']);
            //$cabecera->setValorCambio($request['valor_cambio']);
            $cabecera->setFechaEmision($request['fecha_emision']);
            $cabecera->setFechaVigenciaTimbrado($request['fecha_vigencia_timbrado']);
            $cabecera->setComentario($request['comentario']);
            //$cabecera->setMontoTotal($total);
            //$cabecera->setTotalExenta($total_exenta);
            //$cabecera->setTotalGravada($total_gravada);
            //$cabecera->setTotalIva($total_iva);
            //$cabecera->setUsuarioId($usuario->id);
    
            $cabecera->update();

            /*
            //volvemos a insertar el detalle
            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){

                //para traer despues el costo promedio
                $articulo = Articulo::findOrFail($request['tab_articulo_id'][$i]);

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

            }
            */

            if ($modalidad_pago != 'CO'){
                //Actualizacion de saldo proveedor
                $cuenta = new CuentaProveedor;
                $cuenta->setTipoComprobante('F');
                $cuenta->setComprobanteId($cabecera->getId());
                $cuenta->setMontoComprobante(str_replace('.', '', $cabecera->getMontoTotal()));
                $cuenta->setMontoSaldo(str_replace('.', '', $cabecera->getMontoTotal()));
                $cuenta->save();
            
            } else {
                //Actualizacion de saldo proveedor
                $cuenta = CuentaProveedor::findOrFail($cabecera->getId());
                $cuenta->setMontoComprobante(str_replace('.', '', $cabecera->getMontoTotal()));
                $cuenta->setMontoSaldo(str_replace('.', '', $cabecera->getMontoTotal()));
                $cuenta->update();
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

        return redirect(route('compra.edit'))->with('status', 'Datos guardados correctamente!');
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

            $cabecera = ComprasCab::findOrFail($id);
            $cuenta = CuentaProveedor::where('comprobante_id', $id)->where('tipo_comprobante', 'F')->firstOrFail();
            $nota_credito = NotaCreditoComprasCab::where('compra_cab_id', $id)->firstOrFail();
            $orden_pago = OrdenPagoFacturas::findOrFail($cabecera->getId());

            if (!empty($orden_pago ))  {                
                return back()->withErrors('Compra no se puede eliminar porque tiene una nota de crédito afectada');
            }

            if (!empty($nota_credito ))  {                
                return back()->withErrors('Compra no se puede eliminar porque tiene un pago aplicado');
            }
            
            $modalidad_pago = $cabecera->getTipoFactura();

            if (!empty($cuenta )) {                
                $cuenta->delete();
            }
            
            foreach ($cabecera->pedidosDetalle() as $detalle) {

                //controlamos existencia
                if ($detalle->articulo->getControlExistencia() == true) {
                    //Actualizacion de existencia
                    $existencia = ExistenciaArticulo::where('articulo_id', $detalle->articulo->getId())
                        ->where('sucursal_id', $sucursal->getId())->first();

                    //si aún no existe el artícuo en la tabla de existencia, insertamos un nuevo registro 
                    if (!empty($existencia)){
                        $existencia->actualizaStock('-', $detalle->getCantidad());
                        $existencia->update();                        
                    }   

                }

            }

            $movimiento_articulo = MovimientoArticulo::where('tipo_movimiento', 'C')
            ->where('movimiento_id', $id);
            $movimiento_articulo->delete();

            $cabecera->comprasdetalle()->delete();

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

    public function apiComprasProveedor($cliente_id){
        if (empty($cliente_id)) {
            return [];
        } else {
            $facturas = ComprasCab::where('proveedor_id', $cliente_id)->
                where('estado', 'P')->get();
            /*Filtra por las facturas que tienen saldo.*/
            $facturas = $facturas->filter(function ($factura) {
                return ($factura->getMontoSaldo() > 0);
            });
            return Datatables::of($facturas)
                    ->addColumn('nro_factura', function($facturas){
                        return $facturas->getNroFactura();
                    })
                    ->addColumn('fecha', function($facturas){
                        return $facturas->getFechaEmision();
                    })
                    ->addColumn('moneda', function($facturas){
                        return $facturas->moneda->getDescripcion();
                    })
                    ->addColumn('monto_total', function($facturas){
                        return $facturas->getMontoSaldoFormat();
                    })
                    ->addColumn('comentario', function($facturas){
                        return $facturas->getComentario();
                    })->make(true);
        }
        
    }

    public function apiComprasProveedorOP($cliente_id){
        $articulos_array = [];

        if (empty($cliente_id)) {
            $facturas = ComprasCab::all();
        } else {
            $facturas = ComprasCab::where('proveedor_id', $cliente_id)->
                where('estado', 'P')->get();
        }

        $facturas = $facturas->filter(function ($factura) {
            return ($factura->getMontoSaldo() > 0);
        });

        //dd($facturas);
        
        foreach ($facturas as $factura) {
            $articulos_array[] = array('id'=> $factura->getId(), 'text'=> $factura->getTipoFacturaIndex().' - '.$factura->getNroFactura().' - '.$factura->getFechaEmision());
        }

        return json_encode($articulos_array);
        
    }

    public function apiComprasImportes($compra_id){

        if (!empty($compra_id)) {
            $articulo = collect(ComprasCab::findOrFail($compra_id));
            //$articulo_obj = ComprasCab::findOrFail($compra_id);
            //$articulo = Articulo::findOrFail($articulo_id)->first();
            //$ultimo_costo = $articulo->ultimo_costo; 
            $articulo_obj = ComprasCab::findOrFail($compra_id);
            
            $articulo->put('saldo', $articulo_obj->getMontoSaldo());
            
            return $articulo;
        };
    }

    public function apiCompraDetalle($array_pedidos){
        $cast_array = explode(",",($array_pedidos));

        /*PROBANDO CON DB*/
        $factura_detalle = DB::table('compras_det as cd')
            ->join('compras_cab as c', 'cd.compra_cab_id', '=', 'c.id')
            ->join('articulos as a', 'cd.articulo_id', '=', 'a.id')
            ->select('cd.articulo_id', 'a.codigo', 'a.descripcion', 'cd.porcentaje_iva', 
            DB::raw('ROUND(MIN(cd.costo_unitario), 2) as precio_unitario'),
            DB::raw('ROUND(MAX(cd.porcentaje_descuento), 2) as porcentaje_descuento'),
            DB::raw('ROUND(SUM(cd.cantidad), 2) as cantidad'), 
            DB::raw('ROUND(SUM(cd.monto_descuento), 2) as monto_descuento'), 
            DB::raw('ROUND(SUM(cd.monto_exenta), 2) as monto_exenta'), 
            DB::raw('ROUND(SUM(cd.monto_gravada), 2) as monto_gravada'), 
            DB::raw('ROUND(SUM(cd.monto_iva), 2) as monto_iva'), 
            DB::raw('ROUND(SUM(cd.sub_total), 2) as monto_total'))
            ->whereIn('cd.compra_cab_id', $cast_array)
            ->where('c.estado', 'P')
            ->groupBy('cd.articulo_id', 'a.codigo', 'a.descripcion', 'cd.porcentaje_iva')
            ->get();
        return $factura_detalle;
    }

    public function apiCompras(){
        //en vez de editar, eliminamos en seco si algo se cargó mal
        $permiso_editar = Auth::user()->can('compra.destroy');
        $permiso_eliminar = Auth::user()->can('compra.destroy');
        $permiso_ver = Auth::user()->can('compra.show');
        $sucursal_actual = Auth::user()->empleado->sucursales->first();
        $compras = ComprasCab::where('sucursal_id',$sucursal_actual->getId())->get();

        if ($permiso_editar) {
            if($permiso_eliminar){
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
                            $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $compras->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                            $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                            $puede_eliminar = '<a onclick="deleteData('. $compras->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                            $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                                
                            if ($compras->estado == 'P') {
                                //return $puede_ver.$puede_editar.$puede_eliminar;
                                return $puede_ver.$puede_eliminar;
                            } else {
                                //return $puede_ver.$no_puede_eliminar;
                                return $puede_ver.$no_puede_eliminar;
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
                            $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                            $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                            $puede_eliminar = '<a onclick="deleteData('. $compras->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                            $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
 
                            if ($compras->estado == 'P') {
                                //return $no_puede_ver.$puede_editar.$puede_eliminar;
                                return $no_puede_ver.$puede_eliminar;
                            } else {
                                //return $no_puede_ver.$no_puede_editar.$no_puede_eliminar;
                                return $no_puede_ver.$no_puede_eliminar;
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
                        ->addColumn('monto_total', function($compras){
                            return $compras->getMontoTotal();
                        })
                        ->addColumn('action', function($compras){
                            $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $compras->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                            $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                            if ($compras->estado == 'P') {
                                //return $puede_ver.$puede_editar.$no_puede_eliminar;
                                return $puede_ver.$no_puede_eliminar;
                            } else {
                                //return $puede_ver.$no_puede_editar.$no_puede_eliminar;
                                return $puede_ver.$no_puede_eliminar;
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
                            $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                            $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                            $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                            if ($compras->estado == 'P') {
                                //return $no_puede_ver.$puede_editar.$no_puede_eliminar;
                                return $no_puede_ver.$no_puede_eliminar;
                            } else {
                                //return $no_puede_ver.$no_puede_editar.$no_puede_eliminar;
                                return $no_puede_ver.$no_puede_eliminar;
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
                    ->addColumn('monto_total', function($compras){
                        return $compras->getMontoTotal();
                    })
                    ->addColumn('action', function($compras){
                        $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $compras->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        $puede_eliminar = '<a onclick="deleteData('. $compras->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                        $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                        if ($compras->estado == 'P') {
                            //return $puede_ver.$puede_editar.$puede_eliminar;
                            return $puede_ver.$puede_eliminar;
                        } else {
                            //return $puede_ver.$no_puede_editar.$no_puede_eliminar;
                            return $puede_ver.$no_puede_eliminar;
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
                        $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $compras->id .')" class="btn btn-warning btn-sm" title="Editar Compra"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        $puede_eliminar = '<a onclick="deleteData('. $compras->id .')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></a>';
                        $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                        if ($compras->estado == 'P') {
                            //return $no_puede_ver.$puede_editar.$puede_eliminar;
                            return $no_puede_ver.$puede_eliminar;
                        } else {
                            //return $no_puede_ver.$no_puede_editar.$no_puede_eliminar;
                            return $no_puede_ver.$no_puede_eliminar;
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
                        $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $compras->id .')" class="btn btn-primary btn-sm" title="Ver Compra"><i class="fa fa-eye"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                        //return $puede_ver.$no_puede_editar.$no_puede_eliminar;                        
                        return $puede_ver.$no_puede_eliminar;
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
                        $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Compra" disabled><i class="fa fa-eye"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Compra" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_eliminar = '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';

                        //return $no_puede_ver.$no_puede_editar.$no_puede_eliminar;
                        return $no_puede_ver.$no_puede_eliminar;
                    })->make(true);
            }
        }
    }
}
