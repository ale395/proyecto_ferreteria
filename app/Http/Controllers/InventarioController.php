<?php

namespace App\Http\Controllers;
use Validator;
use App\DatosDefault;
use App\InventarioCab;
use App\InventarioDet;
use App\ExistenciaArticulo;
use App\MovimientoArticulo;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;

use App\Empleado;
use App\Articulo;
use App\Sucursal;
use DB;
use Response;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Collections;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inventario.index');
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
        $sucursal = Auth::user()->empleado->sucursales->first();
       // $sucursales = Sucursal::where('activo',true)->get();  
        $nro_inventario = InventarioCab::max('nro_inventario');

        $nro_inventario = InventarioCab::max('nro_inventario');


        if($nro_inventario) {
            $nro_inventario = $nro_inventario + 1; 
        } else {
            $nro_inventario= 1;       
        }
        

        return view('inventario.create', compact('fecha_actual', 'nro_inventario','sucursal'));
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

            //instanciamos la clase
            $inventario_cab = new InventarioCab();
            $monto_total = 0;
           // $sucursal = Auth::user()->empleado->sucursales->first();
            $usuario = Auth::user();
          //  if (!empty('sucursal')) {
            //    $request['sucursal_id'] = $sucursal->getId();
           // }

            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
                $monto_total = $monto_total + str_replace('.', '', $request['tab_subtotal'][$i]);

            }

            //pasamos los parámetros del request
            $inventario_cab->setNroInventario( $request['nro_inventario']);
            $inventario_cab->setFechaEmision($request['fecha_emision']);   
            $inventario_cab->setSucursalId($request['sucursal_id']);    
            $inventario_cab->setMotivo($request['motivo']); 
            $inventario_cab->setMontototal($monto_total); 
            $inventario_cab->setUsuarioId($usuario->id);
            //guardamos
            $inventario_cab->save();

            //desde aca va el detalle-----------------------------------                    
            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
                $inventario_det = new InventarioDet();
                //datos del articulo.
              //  $articulo_aux = Articulo::where('id', $tab_articulo_id[$i])->first();//para traer algunas cosas del maestro
           
               // $inventario_det->inventario_cab_id =  $inventario_cab->getId(); 
               $inventario_det->setInventarioCabId( $inventario_cab->getId()); 
                $inventario_det->setArticuloId($request['tab_articulo_id'][$i]);            
                $inventario_det->setExistencia(str_replace(',', '.', str_replace('.', '', $request['tab_existencia'][$i])));
                $inventario_det->setCantidad(str_replace(',', '.', str_replace('.', '', $request['tab_cantidad'][$i])));
                $inventario_det->setDiferencia(str_replace(',', '.', str_replace('.', '', $request['tab_diferencia'][$i])));
                $inventario_det->setCostoUnitario(str_replace('.', '', $request['tab_costo_unitario'][$i])); 
                $inventario_det->setSubTotal(str_replace('.', '', $request['tab_subtotal'][$i]));

                $inventario_det->save();

            
             //-----------------------------------------------------------
             //controlamos existencia
 if ($inventario_det->articulo->getControlExistencia() == true) {
    //Actualizacion de existencia
    $existencia = ExistenciaArticulo::where('articulo_id', $inventario_det->articulo->getId())
        ->where('sucursal_id', $request['sucursal_id'])->first();

        //si aún no existe el artícuo en la tabla de existencia, insertamos un nuevo registro 
    if (!empty($existencia) && $inventario_det->getDiferencia()){
        $existencia->actualizaStock('+', $inventario_det->getDiferencia());
        $existencia->update();                     
    } else {
        $existencia_nuevo = new ExistenciaArticulo();

        $existencia_nuevo->setArticuloId($inventario_det->articulo->getId());
        $existencia_nuevo->setSucursalId($request['sucursal_id']);
        $existencia_nuevo->setCantidad($inventario_det->getCantidad());
        $existencia_nuevo->setFechaUltimoInventario($request['fecha_emision']);

        $existencia_nuevo->save();  
    }   

}

        //Actualizacion de la captura de movimiento de articulos
        $movimiento = new MovimientoArticulo;
        //$movimiento->setFecha($inventario_cab->getFechaEmision());   
        $movimiento->setFecha($request['fecha_emision']);
        $movimiento->setTipoMovimiento('I');
        $movimiento->setMovimientoId($inventario_cab->getId());
        $movimiento->setSucursalId($inventario_cab->sucursal->getId());
        $movimiento->setArticuloId($inventario_det->articulo->getId());      
        $movimiento->setCantidad($inventario_det->getCantidad());             

        $movimiento->save();

     }
          DB::commit();
        } catch (\Exception $e) {

            //$error = $e->getMessage();
            //Deshacemos la transaccion
            DB::rollback();
            //volvemos para atras y mostrarmos los errores
            return back()->withErrors( $e->getMessage() )->withInput();
            //return back()->withErrors( $e->getTraceAsString() )->withInput();
        }

        return redirect(route('inventarios.create'))->with('status', 'Datos guardados correctamente!');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
   
        $inventario_cab = InventarioCab::findOrFail($id);
        return view('inventario.show',compact('inventario_cab'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $inventario_cab = InventarioCab::findOrFail($id);
            return view('inventario.edit',compact('inventario_cab'));
    
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
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            //instanciamos la clase
            $inventario_cab = InventarioCab::findOrFail($id);
            $monto_total=0;
            $usuario = Auth::user();

         
            for ($i=0; $i < collect($request['tab_subtotal'])->count(); $i++){

                $subtotal =  $request['tab_subtotal'][$i];
                $subtotal = str_replace('.', '', $subtotal);
                //$subtotal = str_replace(',', '.', $subtotal);
                
                $monto_total = $monto_total + $subtotal;
            }

            //pasamos los parámetros del request

              $inventario_cab->setFechaEmision($request['fecha_emision']);   
              $inventario_cab->setSucursalId($request['sucursal_id']);    
              $inventario_cab->setMotivo($request['motivo']); 
              $inventario_cab->setMontototal($monto_total); 
              $inventario_cab->setUsuarioId($usuario->id);

            //guardamos los cambios
            $inventario_cab->update();

            //borramos el detalle anterior
            $inventario_cab->inventarioDetalle()->delete();

            //desde aca va el nuevo detalle-----------------------------------         
            //lo que trae directamente del request
            $tab_articulo_id = $request['tab_articulo_id'];
            $tab_existencia = $request['tab_existencia'];
            $tab_cantidad = $request['tab_cantidad'];
            $tab_costounitario = $request['tab_costounitario'];        
            $tab_subtotal = $request['tab_subtotal'];
           
            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){

                $existencia = $tab_existencia[$i];
                $existencia = str_replace('.', '', $existencia);

                $cantidad = $tab_cantidad[$i];
                $cantidad = str_replace('.', '', $cantidad);

                $subtotal = $tab_subtotal[$i];
                $subtotal = str_replace('.', '', $subtotal);
      

                //datos del articulo.
                $articulo_aux = Articulo::where('id', $tab_articulo_id[$i])->first();//para traer algunas cosas del maestro    


              
                $inventario_det = new InventarioDet();
                $inventario_det->setInventarioCabId( $inventario_cab->getId()); 
                $inventario_det->setArticuloId($request['tab_articulo_id'][$i]);            
                $inventario_det->setExistencia(str_replace(',', '.', str_replace('.', '', $request['tab_existencia'][$i])));
                $inventario_det->setCantidad(str_replace(',', '.', str_replace('.', '', $request['tab_cantidad'][$i])));
                $inventario_det->setCostoUnitario(str_replace('.', '', $request['tab_costo_unitario'][$i])); 
                $inventario_det->setSubTotal(str_replace('.', '', $request['tab_subtotal'][$i]));

                $inventario_det->save();

            }
             //-----------------------------------------------------------

            DB::commit();
        } catch (\Exception $e) {

            //$error = $e->getMessage();
            //Deshacemos la transaccion
            DB::rollback();
            //volvemos para atras y retornamos un mensaje de error
            //return back()->withErrors('Ha ocurrido un error. Favor verificar')->withInput();
            return back()->withErrors( $e->getMessage() )->withInput();
            //return back()->withErrors( $e->getTraceAsString() )->withInput();

        }
        return redirect()->route('inventarios.show', ['inventarios' => $inventario_cab->getId()])->with('status', 'Pedido guardado correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $movimiento_articulo = MovimientoArticulo::where('tipo_movimiento', 'I')
        ->where('movimiento_id', $id);
        $movimiento_articulo->delete();
    
        $inventario_cab = InventarioCab::findOrFail($id);
        $detalles_fact = $inventario_cab->inventarioDetalle;
        foreach ($detalles_fact as $detalle) {
            if ($detalle->articulo->getControlExistencia()) {
                $existencia = ExistenciaArticulo::where('articulo_id',  $detalle->articulo->getId())
                ->where('sucursal_id', $inventario_cab->sucursal->getId())->first();
                $existencia->setCantidad($existencia->getCantidadNumber() - $detalle->getDiferencia());
                $existencia->update();
            }
        }

        $inventario_cab->inventarioDetalle()->delete();
        return InventarioCab::destroy($id);
    }

    public function impresionInventario($id){
        $inventario_cab = InventarioCab::findOrFail($id);
        $pdf = \PDF::loadView('reportesInventarios.impresionInventario', compact('inventario_cab'));
        return $pdf->stream('Inventario.pdf', array('Attachment'=>0));
    }
    
    public function apiInventarios() {
        $permiso_editar = Auth::user()->can('inventarios.edit');
        $permiso_eliminar = Auth::user()->can('inventario.destroy');
        $permiso_ver = Auth::user()->can('inventarios.show');
       $inventario = InventarioCab::all();
       //dd($permiso_ver);
       if ($permiso_editar) {
                if ($permiso_ver) {
                    return Datatables::of($inventario)
                    ->addColumn('fecha', function($inventario){
                        return $inventario->getFechaEmision();
                    })
                    ->addColumn('sucursal', function($inventario){
                        return $inventario->sucursal->getNombre();
                    })
                    ->addColumn('monto_total', function($inventario){
                        return $inventario->getMontoTotal();
                    })
                    ->addColumn('action', function($inventario){
                        $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $inventario->id .')" class="btn btn-primary btn-sm" title="Ver Factura"><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $inventario->id .')" class="btn btn-warning btn-sm" title="Editar Factura"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        $puede_borrar = '<a data-toggle="tooltip" data-placement="top" onclick="deleteData('. $inventario->id .')" class="btn btn-danger btn-sm" title="Eliminar Pedido"><i class="fa fa-trash-o"></i></a>';

                       // return $puede_ver.$puede_editar.$puede_borrar;
                       return $puede_ver.$puede_borrar;

                                        })->make(true);              


                                    } else {
                    return Datatables::of($inventario)
                    ->addColumn('fecha', function($inventario){
                        return $inventario->getFechaEmision();
                    })
                    ->addColumn('sucursal', function($inventario){
                        return $inventario->sucursal->getNombre();
                    })
                    ->addColumn('monto_total', function($inventario){
                        return $inventario->getMontoTotal();
                    })
                    ->addColumn('action', function($inventario){
                        $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Factura" disabled><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $inventario->id .')" class="btn btn-warning btn-sm" title="Editar Factura"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        return $puede_ver.$puede_editar;
                    })->make(true);
                }
        } else {
            if ($permiso_ver) {
                return Datatables::of($inventario)
                ->addColumn('fecha', function($inventario){
                    return $inventario->getFechaEmision();
                })
                ->addColumn('sucursal', function($inventario){
                    return $inventario->sucursal->getNombre();
                })
                ->addColumn('monto_total', function($inventario){
                    return $inventario->getMontoTotal();
                })
                ->addColumn('action', function($inventario){
                    $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $inventario->id .')" class="btn btn-primary btn-sm" title="Ver Factura"><i class="fa fa-eye"></i></a> ';
                    $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                    return $puede_ver.$puede_editar;                                })->make(true);
            } else{
                return Datatables::of($inventario)
                ->addColumn('fecha', function($inventario){
                    return $inventario->getFechaEmision();
                })
                ->addColumn('sucursal', function($inventario){
                    return $inventario->sucursal->getNombre();
                })
                ->addColumn('monto_total', function($inventario){
                    return $inventario->getMontoTotal();
                })
                ->addColumn('action', function($inventario){
                    $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Factura" disabled><i class="fa fa-eye"></i></a> ';
                    $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                    return $no_puede_ver.$puede_editar;
                                })->make(true);
            }
        }
    }
}
