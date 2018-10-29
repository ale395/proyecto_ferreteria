<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\OrdenCompraFormRequest;
use App\OrdenCompraCab;
use App\OrdenCompraDet;
use App\Proveedor;
use App\Moneda;
use App\Articulo;
use App\DatosDefault;
use App\Impuesto;
use DB;
use Response;
use Illuminate\Support\Collections;


class OrdenCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ordencompra.index');
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
        $nro_orden_compra = OrdenCompraCab::max('nro_orden');
        $moneda = $datos_default->moneda;
        $cambio = 1;
        //$nro_orden = DB::table('orden_compras_cab')->select(DB::raw('coalesce(max(nro_orden),0) + 1 as nro_orden'))->get();
        //$nro_orden = DB::table('orden_compras_cab')->orderBy('nro_orden', 'desc')->first();    
                    
        if($nro_orden_compra) {
            $nro_orden = $nro_orden_compra + 1; 
        } else {
            $nro_orden = 1;       
        }
        

        return view('ordencompra.create',compact('fecha_actual', 'nro_orden', 'moneda', 'cambio'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrdenCompraFormRequest $request)
    {
        try {
            DB::beginTransaction();

            //instanciamos la clase
            $orden_compra = new OrdenCompraCab();
            $total = 0;
            $sucursal = Auth::user()->empleado->sucursales->first();

            if (!empty('sucursal')) {
                $request['sucursal_id'] = $sucursal->getId();
            }

            for ($i=0; $i < collect($request['tab_subtotal'])->count(); $i++){

                $subtotal =  $request['tab_subtotal'][$i];
                $subtotal = str_replace('.', '', $subtotal);
                $subtotal = str_replace(',', '.', $subtotal);
                
                $total = $total + $subtotal;
            }

            //pasamos los parámetros del request
            $orden_compra->nro_orden = $request['nro_orden'];
            $orden_compra->proveedor_id = $request['proveedor_id'];
            $orden_compra->fecha_emision = $request['fecha_emision'];   
            $orden_compra->sucursal_id = $request['sucursal_id'];     
            $orden_compra->moneda_id = $request['moneda_id'];
            $orden_compra->valor_cambio = $request['valor_cambio'];
            $orden_compra->monto_total = $total;
            $orden_compra->estado = 'A';

            //guardamos
            $orden_compra->save();

            //desde aca va el detalle-----------------------------------         
            //lo que trae directamente del request
            $tab_articulo_id = $request['tab_articulo_id'];
            $tab_cantidad = $request['tab_cantidad'];
            $tab_costounitario = $request['tab_costounitario'];        
            $tab_subtotal = $request['tab_subtotal'];
           
            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){

                $cantidad = $tab_cantidad[$i];
                $cantidad = str_replace('.', '', $cantidad);
                //$cantidad = str_replace(',', '.', $cantidad);

                $costo_unitario = $tab_costounitario[$i];
                $costo_unitario = str_replace('.', '', $costo_unitario);
                //$costo_unitario = str_replace(',', '.', $costo_unitario);

                $subtotal = $tab_subtotal[$i];
                $subtotal = str_replace('.', '', $subtotal);
                //$subtotal = str_replace(',', '.', $subtotal);

                //datos del articulo.
                $articulo_aux = Articulo::where('id', $tab_articulo_id[$i])->first();//para traer algunas cosas del maestro    
                $iva = Impuesto::where('id',$articulo_aux->impuesto_id)->first();//para traer el porcentaje del IVA
                
                $costo_promedio = $articulo_aux->costo_promedio; 
                $porcentaje_iva = $iva->porcentaje;
                $coheficiente_iva =  1 + ($porcentaje_iva/100); //coheficiente utilizado para calcular los valores del IVA

                if($porcentaje_iva == 0){
                    $total_exenta = $tab_subtotal[$i];
                    $total_gravada = 0;
                    $total_iva = 0;    
                } else {
                    $total_exenta = 0;
                    $total_gravada = $subtotal;
                    $total_iva = $subtotal / $coheficiente_iva;            
                }

                $orden_compra_detalle = new OrdenCompraDet();

                $orden_compra_detalle->orden_compra_cab_id = $orden_compra->id; 
                $orden_compra_detalle->articulo_id = $tab_articulo_id[$i];
                $orden_compra_detalle->cantidad = $cantidad;
                $orden_compra_detalle->costo_unitario = $costo_unitario;
                $orden_compra_detalle->costo_promedio = $costo_promedio;
                $orden_compra_detalle->sub_total = $subtotal;
                $orden_compra_detalle->porcentaje = $porcentaje_iva;
                $orden_compra_detalle->total_exenta = $total_exenta;
                $orden_compra_detalle->total_gravada = $total_gravada;
                $orden_compra_detalle->total_iva = $total_iva;

                $orden_compra_detalle->save();

            }
             //-----------------------------------------------------------

            DB::commit();
        } catch (\Exception $e) {

            //$error = $e->getMessage();
            //Deshacemos la transaccion
            DB::rollback();
            //volvemos para atras y mostrarmos los errores
            return back()->withErrors( $e->getMessage() )->withInput();
            //return back()->withErrors( $e->getTraceAsString() )->withInput();
        }

        return redirect(route('ordencompra.create'))->with('status', 'Datos guardados correctamente!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orden_compra = DB::table('orden_compras_cab as o')
        ->join('proveedores as p', 'p.id','=', 'o.proveedor_id')
        ->join('monedas as m', 'm.id','=', 'o.moneda_id')
        ->select('o.id', 'o.nro_orden', 'o.fecha_emision', 'o.proveedor_id',
        DB::raw("CONCAT(p.codigo, ' ', p.nombre) as proveedor"),
        'o.moneda_id','m.codigo', 'm.descripcion', 'o.valor_cambio', 'o.monto_total')
        ->where('o.id','=',$id)->first();

        $orden_compra_detalle = DB::table('orden_compras_det as od')
        ->join('articulos as a', 'a.id','=', 'od.articulo_id')
        ->select('od.orden_compra_cab_id', 'a.codigo', 'a.descripcion', 'od.cantidad',
        'od.costo_unitario', 'od.sub_total','od.porcentaje','od.total_exenta', 
        'od.total_gravada', 'od.total_iva')
        ->where('od.orden_compra_cab_id','=',$id)
        ->get();

        return view('ordencompra.show',compact('orden_compra', 'orden_compra_detalle'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*
        $orden_compra = OrdenCompraCab::findOrFail($id);
        return view('ordencompra.edit',compact('orden_compra'));
        */
        try {
            $orden_compra = OrdenCompraCab::findOrFail($id);
            return view('ordencompra.edit',compact('orden_compra'));
    
        } catch (\Exception $e) {
            return redirect()->back()->with('warning', 'Ocurrió un error!');
        }
    
    } 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrdenCompraFormRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            //instanciamos la clase
            $orden_compra = new OrdenCompraCab();
            $total = 0;
            $sucursal = Auth::user()->empleado->sucursales->first();

            if (!empty('sucursal')) {
                $request['sucursal_id'] = $sucursal->getId();
            }

            for ($i=0; $i < collect($request['tab_subtotal'])->count(); $i++){

                $subtotal =  $request['tab_subtotal'][$i];
                $subtotal = str_replace('.', '', $subtotal);
                //$subtotal = str_replace(',', '.', $subtotal);
                
                $total = $total + $subtotal;
            }

            //pasamos los parámetros del request
            $orden_compra->nro_orden = $request['nro_orden'];
            $orden_compra->proveedor_id = $request['proveedor_id'];
            $orden_compra->fecha_emision = $request['fecha_emision'];   
            $orden_compra->sucursal_id = $request['sucursal_id'];     
            $orden_compra->moneda_id = $request['moneda_id'];
            $orden_compra->valor_cambio = $request['valor_cambio'];
            $orden_compra->monto_total = $total;
            $orden_compra->estado = $request['estado'];

            //guardamos los cambios
            $orden_compra->save();

            //borramos el detalle anterior
            $orden_compra->OrdenCompraDetalle()->delete();

            //desde aca va el nuevo detalle-----------------------------------         
            //lo que trae directamente del request
            $tab_articulo_id = $request['tab_articulo_id'];
            $tab_cantidad = $request['tab_cantidad'];
            $tab_costounitario = $request['tab_costounitario'];        
            $tab_subtotal = $request['tab_subtotal'];
           
            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){

                $cantidad = $tab_cantidad[$i];
                $cantidad = str_replace('.', '', $cantidad);
                //$cantidad = str_replace(',', '.', $cantidad);

                $costo_unitario = $tab_costounitario[$i];
                $costo_unitario = str_replace('.', '', $costo_unitario);
                //$costo_unitario = str_replace(',', '.', $costo_unitario);

                $subtotal = $tab_subtotal[$i];
                $subtotal = str_replace('.', '', $subtotal);
                //$subtotal = str_replace(',', '.', $subtotal);

                //datos del articulo.
                $articulo_aux = Articulo::where('id', $tab_articulo_id[$i])->first();//para traer algunas cosas del maestro    
                $iva = Impuesto::where('id',$articulo_aux->impuesto_id)->first();//para traer el porcentaje del IVA
                
                $costo_promedio = $articulo_aux->costo_promedio; 
                $porcentaje_iva = $iva->porcentaje;
                $coheficiente_iva =  1 + ($porcentaje_iva/100); //coheficiente utilizado para calcular los valores del IVA

                if($porcentaje_iva == 0){
                    $total_exenta = $tab_subtotal[$i];
                    $total_gravada = 0;
                    $total_iva = 0;    
                } else {
                    $total_exenta = 0;
                    $total_gravada = $subtotal;
                    $total_iva = $subtotal / $coheficiente_iva;            
                }

                $orden_compra_detalle = new OrdenCompraDet();

                $orden_compra_detalle->orden_compra_cab_id = $orden_compra->id; 
                $orden_compra_detalle->articulo_id = $tab_articulo_id[$i];
                $orden_compra_detalle->cantidad = $cantidad;
                $orden_compra_detalle->costo_unitario = $costo_unitario;
                $orden_compra_detalle->costo_promedio = $costo_promedio;
                $orden_compra_detalle->sub_total = $subtotal;
                $orden_compra_detalle->porcentaje = $porcentaje_iva;
                $orden_compra_detalle->total_exenta = $total_exenta;
                $orden_compra_detalle->total_gravada = $total_gravada;
                $orden_compra_detalle->total_iva = $total_iva;

                $orden_compra_detalle->save();

            }
             //-----------------------------------------------------------

            DB::commit();
        } catch (\Exception $e) {

            //$error = $e->getMessage();
            //Deshacemos la transaccion
            DB::rollback();
            //volvemos para atras y retornamos un mensaje de error
            return back()->withErrors('Ha ocurrido un error. Favor verificar')->withInput();
            //return back()->withErrors( $e->getMessage() )->withInput();
            //return back()->withErrors( $e->getTraceAsString() )->withInput();

        }

        return redirect(route('ordencompra.index'))->with('status', 'Datos modificados correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $orden_compra = OrdenCompraCab::findOrFail($id);
        $orden_compra->pedidosDetalle()->delete();
        return OrdenCompraCab::destroy($id);
    }

    public function apiOrdenCompra()
    {
        $permiso_editar = Auth::user()->can('ordencompra.edit');
        $permiso_eliminar = Auth::user()->can('ordencompra.destroy');
        $permiso_ver = Auth::user()->can('ordencompra.show');
        //$ordenes_compra = Proveedor::all();
        $ordenes_compra = DB::table('orden_compras_cab as o')
        ->join('proveedores as p', 'p.id','=', 'o.proveedor_id')
        ->join('monedas as m', 'm.id','=', 'o.moneda_id')
        ->select('o.id', 'o.nro_orden', 'o.fecha_emision as fecha', DB::raw("CONCAT(p.razon_social, ' - ' , p.nombre) as proveedor"),
        'm.codigo', 'o.monto_total');


        if ($permiso_editar) {
            if ($permiso_eliminar) {
                if ($permiso_ver) {
                    return Datatables::of($ordenes_compra)
                    ->addColumn('action', function($ordenes_compra){
                        return '<a onclick="showForm('. $ordenes_compra->id .')" class="btn btn-primary btn-sm" title="Ver Orden de Compra"><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $ordenes_compra->id .')" class="btn btn-warning btn-sm" title="Editar Orden de Compra"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a onclick="deleteData('. $ordenes_compra->id .')" class="btn btn-danger btn-sm" title="Eliminar Orden de Compra"><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                } else{
                    return Datatables::of($ordenes_compra)
                    ->addColumn('action', function($ordenes_compra){
                        return '<a class="btn btn-primary btn-sm" title="Ver Orden de Compra"  disabled><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $ordenes_compra->id .')" class="btn btn-warning btn-sm" title="Editar Orden de Compra"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a onclick="deleteData('. $ordenes_compra->id .')" class="btn btn-danger btn-sm" title="Eliminar Orden de Compra"><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                }
            } else {
                if ($permiso_ver) {
                    return Datatables::of($ordenes_compra)
                    ->addColumn('action', function($ordenes_compra){
                        return '<a onclick="showForm('. $ordenes_compra->id .')" class="btn btn-primary btn-sm" title="Ver Orden de Compra"><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $ordenes_compra->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                               '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                    })->make(true);
                } else{
                    return Datatables::of($ordenes_compra)
                    ->addColumn('action', function($ordenes_compra){
                        return '<a class="btn btn-primary btn-sm" title="Ver Orden de Compra" disabled><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $ordenes_compra->id .')" class="btn btn-warning btn-sm" title="Editar Orden de Compra"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a class="btn btn-danger btn-sm" title="Eliminar Orden de Compra" disabled><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                }
            }
        } elseif ($permiso_eliminar) {
            if ($permiso_ver) {
                return Datatables::of($ordenes_compra)
                ->addColumn('action', function($ordenes_compra){
                    return '<a onclick="showForm('. $ordenes_compra->id .')" class="btn btn-primary btn-sm" title="Ver Orden de Compra"><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $ordenes_compra->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else{
                return Datatables::of($ordenes_compra)
                ->addColumn('action', function($ordenes_compra){
                    return '<a class="btn btn-primary btn-sm" title="Ver Orden de Compra" disabled><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $ordenes_compra->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } else {
            if ($permiso_ver) {
                return Datatables::of($ordenes_compra)
                ->addColumn('action', function($ordenes_compra){
                    return '<a onclick="showForm('. $ordenes_compra->id .')" class="btn btn-primary btn-sm" title="Ver Orden de Compra"><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else{
                return Datatables::of($ordenes_compra)
                ->addColumn('action', function($ordenes_compra){
                    return '<a class="btn btn-primary btn-sm" title="Ver Orden de Compra"  disabled><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        }
    }
}
