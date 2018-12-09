<?php

namespace App\Http\Controllers;
use Validator;
use App\DatosDefault;
use App\AjusteInventarioCab;
use App\AjusteInventarioDet;
use App\ExistenciaArticulo;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;

use App\Empleado;
use App\Articulo;
use App\ConceptoAjuste;
use App\Sucursal;
use DB;
use Response;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Collections;

class AjusteInventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ajusteInventario.index');
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
        $concepto_ajuste =   ConceptoAjuste::all();
       // $sucursales = Sucursal::where('activo',true)->get();  
        $nro_ajuste_inventario = AjusteInventarioCab::max('nro_ajuste');



        if($nro_ajuste_inventario) {
            $nro_ajuste = $nro_ajuste_inventario + 1; 
        } else {
            $nro_ajuste= 1;       
        }
        

        return view('ajusteInventario.create', compact('fecha_actual', 'nro_ajuste','concepto_ajuste'));
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
            $ajuste_inventario_cab = new AjusteInventarioCab();
            $monto_total = 0;
            $sucursal = Auth::user()->empleado->sucursales->first();
            $usuario = Auth::user();
            if (!empty('sucursal')) {
                $request['sucursal_id'] = $sucursal->getId();
            }

            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
                $monto_total = $monto_total + str_replace('.', '', $request['tab_subtotal'][$i]);

            }

            //pasamos los parámetros del request
            $ajuste_inventario_cab->setNroAjuste( $request['nro_ajuste']);
            $ajuste_inventario_cab->setFechaEmision($request['fecha_emision']);   
            $ajuste_inventario_cab->setConceptoAjusteId($request['concepto_ajuste_id']);   
            $ajuste_inventario_cab->setSucursalId($request['sucursal_id']);    
            $ajuste_inventario_cab->setMotivo($request['motivo']); 
            $ajuste_inventario_cab->setMontototal($monto_total); 
            $ajuste_inventario_cab->setUsuarioId($usuario->id);
            //guardamos
            $ajuste_inventario_cab->save();

            //desde aca va el detalle-----------------------------------                    
            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){
                $ajuste_inventario_det = new AjusteInventarioDet();
                //datos del articulo.
              //  $articulo_aux = Articulo::where('id', $tab_articulo_id[$i])->first();//para traer algunas cosas del maestro
           
                $ajuste_inventario_det->setAjusteInventarioCabId( $ajuste_inventario_cab->getId()); 
                $ajuste_inventario_det->setArticuloId($request['tab_articulo_id'][$i]);            
                $ajuste_inventario_det->setCantidad(str_replace(',', '.', str_replace('.', '', $request['tab_cantidad'][$i])));
                $ajuste_inventario_det->setCostoUnitario(str_replace('.', '', $request['tab_costo_unitario'][$i])); 
                $ajuste_inventario_det->setSubTotal(str_replace('.', '', $request['tab_subtotal'][$i]));

                $ajuste_inventario_det->save();

            }
             //-----------------------------------------------------------
 //controlamos existencia
 if ($ajuste_inventario_det->articulo->getControlExistencia() == true) {
    //Actualizacion de existencia
    $existencia = ExistenciaArticulo::where('articulo_id', $ajuste_inventario_det->articulo->getId())
        ->where('sucursal_id', $sucursal->getId())->first();

    //si aún no existe el artícuo en la tabla de existencia, insertamos un nuevo registro 
    if (!empty($existencia) &&  $ajuste_inventario_cab->conceptoAjuste->getSignoOperacion()=='+'){
        $existencia->actualizaStock('+', $ajuste_inventario_det->getCantidad());
        $existencia->update();   
    }elseif(!empty($existencia) &&  $ajuste_inventario_cab->conceptoAjuste->getSignoOperacion()=='-') {
        $existencia->actualizaStock('-', $ajuste_inventario_det->getCantidad());
        $existencia->update();                     
    } else {
        $existencia_nuevo = new ExistenciaArticulo();

        $existencia_nuevo->setArticuloId($ajuste_inventario_det->articulo->getId());
        $existencia_nuevo->setSucursalId($sucursal->getId());
        $existencia_nuevo->setCantidad($ajuste_inventario_det->getCantidad());
        $existencia_nuevo->setFechaUltimoInventario($request['fecha_emision']);

        $existencia_nuevo->save();  
    }   

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

        return redirect(route('ajustesInventarios.create'))->with('status', 'Datos guardados correctamente!');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
   
        $ajuste_inventario_cab = AjusteInventarioCab::findOrFail($id);
        return view('ajusteInventario.show',compact('ajuste_inventario_cab'));
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
            $ajuste_inventario_cab = AjusteInventarioCab::findOrFail($id);
            return view('ajusteInventario.edit',compact('ajuste_inventario_cab'));
    
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
            $ajuste_inventario_cab = AjusteInventarioCab::findOrFail($id);
            $monto_total = 0;
            $sucursal = Auth::user()->empleado->sucursales->first();

            if (!empty('sucursal')) {
                $request['sucursal_id'] = $sucursal->getId();
            }
         
            for ($i=0; $i < collect($request['tab_subtotal'])->count(); $i++){

                $subtotal =  $request['tab_subtotal'][$i];
                $subtotal = str_replace('.', '', $subtotal);
                //$subtotal = str_replace(',', '.', $subtotal);
                
                $monto_total = $monto_total + $subtotal;
            }

            //pasamos los parámetros del request
          //  $ajuste_inventario_cab->empleado_id = $request['empleado_id'];
            $ajuste_inventario_cab->fecha_emision = $request['fecha_emision']; 
            $ajuste_inventario_cab->concepto_ajuste_id = $request['concepto_ajuste_id'];       
            $ajuste_inventario_cab->sucursal_id = $request['sucursal_id'];     
            $ajuste_inventario_cab->monto_total = $monto_total;
            $ajuste_inventario_cab->motivo = $request['motivo'];

            //guardamos los cambios
            $ajuste_inventario_cab->update();

            //borramos el detalle anterior
            $ajuste_inventario_det->AjusteInventarioDetalle()->delete();

            //desde aca va el nuevo detalle-----------------------------------         
            //lo que trae directamente del request
            $tab_articulo_id = $request['tab_articulo_id'];
            $tab_cantidad = $request['tab_cantidad'];
            $tab_costounitario = $request['tab_costounitario'];        
            $tab_subtotal = $request['tab_subtotal'];
           
            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){

                $cantidad = $tab_cantidad[$i];
                $cantidad = str_replace('.', '', $cantidad);

                $subtotal = $tab_subtotal[$i];
                $subtotal = str_replace('.', '', $subtotal);
      

                //datos del articulo.
                $articulo_aux = Articulo::where('id', $tab_articulo_id[$i])->first();//para traer algunas cosas del maestro    


              
                $ajuste_inventario_det = new AjusteInventarioDet();

                $ajuste_inventario_det->ajuste_inventario_cab_id = $ajuste_inventario->id; 
                $ajuste_inventario_det->articulo_id = $tab_articulo_id[$i];
                $ajuste_inventario_det->cantidad = $cantidad;
                $ajuste_inventario_det->costo_unitario = $costo_unitario;

                $ajuste_inventario_det->sub_total = $subtotal;

                $ajuste_inventario_det->save();

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

        return redirect(route('ajusteInventario.index'))->with('status', 'Datos modificados correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ajuste_inventario_cab = AjusteInventarioCab::findOrFail($id);
        $ajuste_inventario_cab->ajustesDetalle()->delete();
        return AjusteInventarioCab::destroy($id);
    }

    public function apiAjustesInventarios() {
        $permiso_editar = Auth::user()->can('ajustesInventarios.edit');
      //  $permiso_eliminar = Auth::user()->can('ajusteInventario.destroy');
        $permiso_ver = Auth::user()->can('ajustesInventarios.show');
       $ajuste_inventario = AjusteInventarioCab::all();
       //dd($permiso_ver);
       if ($permiso_editar) {
                if ($permiso_ver) {
                    return Datatables::of($ajuste_inventario)
                    ->addColumn('fecha', function($ajuste_inventario){
                        return $ajuste_inventario->getFechaEmision();
                    })
                    ->addColumn('sucursal', function($ajuste_inventario){
                        return $ajuste_inventario->sucursal->getNombre();
                    })
                    ->addColumn('concepto_ajuste', function($ajuste_inventario){
                        return $ajuste_inventario->conceptoAjuste->getDescripcion();
                    })
                    ->addColumn('monto_total', function($ajuste_inventario){
                        return $ajuste_inventario->getMontoTotal();
                    })
                    ->addColumn('action', function($ajuste_inventario){
                        $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $ajuste_inventario->id .')" class="btn btn-primary btn-sm" title="Ver Factura"><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $ajuste_inventario->id .')" class="btn btn-warning btn-sm" title="Editar Factura"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        return $puede_ver;

                                        })->make(true);              


                                    } else {
                    return Datatables::of($ajuste_inventario)
                    ->addColumn('fecha', function($ajuste_inventario){
                        return $ajuste_inventario->getFechaEmision();
                    })
                    ->addColumn('sucursal', function($ajuste_inventario){
                        return $ajuste_inventario->sucursal->getNombre();
                    })
                    ->addColumn('concepto_ajuste', function($ajuste_inventario){
                        return $ajuste_inventario->conceptoAjuste->getDescripcion();
                    })
                    ->addColumn('monto_total', function($ajuste_inventario){
                        return $ajuste_inventario->getMontoTotal();
                    })
                    ->addColumn('action', function($ajuste_inventario){
                        $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Factura" disabled><i class="fa fa-eye"></i></a> ';
                        $puede_editar = '<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $ajuste_inventario->id .')" class="btn btn-warning btn-sm" title="Editar Factura"><i class="fa fa-pencil-square-o"></i></a> ';
                        $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                        return $puede_ver;
                    })->make(true);
                }
        } else {
            if ($permiso_ver) {
                return Datatables::of($ajuste_inventario)
                ->addColumn('fecha', function($ajuste_inventario){
                    return $ajuste_inventario->getFechaEmision();
                })
                ->addColumn('sucursal', function($ajuste_inventario){
                    return $ajuste_inventario->sucursal->getNombre();
                })
                ->addColumn('concepto_ajuste', function($ajuste_inventario){
                    return $ajuste_inventario->conceptoAjuste->getDescripcion();
                })
                ->addColumn('monto_total', function($ajuste_inventario){
                    return $ajuste_inventario->getMontoTotal();
                })
                ->addColumn('action', function($ajuste_inventario){
                    $puede_ver = '<a data-toggle="tooltip" data-placement="top" onclick="showForm('. $ajuste_inventario->id .')" class="btn btn-primary btn-sm" title="Ver Factura"><i class="fa fa-eye"></i></a> ';
                    $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                    return $puede_ver/*.$no_puede_editar*/;
                                })->make(true);
            } else{
                return Datatables::of($ajuste_inventario)
                ->addColumn('fecha', function($ajuste_inventario){
                    return $ajuste_inventario->getFechaEmision();
                })
                ->addColumn('sucursal', function($ajuste_inventario){
                    return $ajuste_inventario->sucursal->getNombre();
                })
                ->addColumn('concepto_ajuste', function($ajustes_inventario){
                    return $ajustes_inventario->conceptoAjuste->getDescripcion();
                })
                ->addColumn('monto_total', function($ajuste_inventario){
                    return $ajuste_inventario->getMontoTotal();
                })
                ->addColumn('action', function($ajuste_inventario){
                    $no_puede_ver = '<a data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm" title="Ver Factura" disabled><i class="fa fa-eye"></i></a> ';
                    $no_puede_editar = '<a data-toggle="tooltip" data-placement="top" class="btn btn-warning btn-sm" title="Editar Factura" disabled><i class="fa fa-pencil-square-o"></i></a> ';
                    return $no_puede_ver/*.$no_puede_editar*/;
                                })->make(true);
            }
        }
    }
}
