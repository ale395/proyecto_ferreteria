<?php

namespace App\Http\Controllers;
use Validator;
use App\DatosDefault;
use App\AjusteInventarioCab;
use App\AjusteInventarioDet;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;

use App\Empleado;
use App\Articulo;
use App\ConceptoAjuste;
use App\Sucursal;
use DB;
use Response;
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
        $conceptos_ajustes =   ConceptoAjuste::all();
        

        $sucursales = Sucursal::where('activo',true)->get();
          
        $nro_ajuste_inventario = AjusteInventarioCab::max('nro_ajuste');



        if($nro_ajuste_inventario) {
            $nro_ajuste = $nro_ajuste_inventario + 1; 
        } else {
            $nro_ajuste= 1;       
        }
        

        return view('ajusteInventario.create', compact('fecha_actual', 'nro_ajuste','conceptos_ajustes','sucursales'));
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
            $cantidad_total = 0;
            $sucursal = Auth::user()->empleado->sucursales->first();
            
            $nro_ajuste_inventario_cab = AjusteInventarioCab::max('nro_ajuste');
            if (!empty('sucursal')) {
                $request['sucursal_id'] = $sucursal->getId();
            }

            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){

                $subtotal =  $request['tab_subtotal'][$i];
                $subtotal = str_replace('.', '', $subtotal);
                $subtotal = str_replace(',', '.', $subtotal);
                
                $cantidad_total = $cantidad_total + $subtotal;
            }

            //pasamos los parámetros del request
            $ajuste_inventario_cab->nro_ajuste = $request['nro_ajuste'];
            $ajuste_inventario_cab->empleado_id = $request['empleado_id'];
            $ajuste_inventario_cab->fecha_emision = $request['fecha_emision'];   
            $ajuste_inventario_cab->concepto_ajuste_id = $request['concepto_ajuste_id'];   
            $ajuste_inventario_cab->sucursal_id = $request['sucursal_id'];    
            $ajuste_inventario_cab->motivo=$request['motivo']; 
            $ajuste_inventario_cab->cantidad_total = $cantidad_total;

            //guardamos
            $ajuste_inventario_cab->save();

            //desde aca va el detalle-----------------------------------         
            //lo que trae directamente del request
            $tab_articulo_id = $request['tab_articulo_id'];
            $tab_cantidad = $request['tab_cantidad'];      
            $tab_subtotal = $request['tab_subtotal'];
           
            for ($i=0; $i < collect($request['tab_articulo_id'])->count(); $i++){

                $cantidad = $tab_cantidad[$i];
                $cantidad = str_replace('.', '', $cantidad);
                //$cantidad = str_replace(',', '.', $cantidad);


                $subtotal = $tab_subtotal[$i];
                $subtotal = str_replace('.', '', $subtotal);
                //$subtotal = str_replace(',', '.', $subtotal);

                //datos del articulo.
                $articulo_aux = Articulo::where('id', $tab_articulo_id[$i])->first();//para traer algunas cosas del maestro    
                

                $ajuste_inventario_det = new AjusteInventarioDet();

                $ajuste_inventario_det->ajuste_inventario_cab_id = $ajuste_inventario_cab->id; 
                $ajuste_inventario_det->articulo_id = $tab_articulo_id[$i];
                $ajuste_inventario_det->cantidad = $cantidad;
                $ajuste_inventario_det->sub_total = $subtotal;


                $ajuste_inventario_det->save();

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

        return redirect(route('ajusteInventario.create'))->with('status', 'Datos guardados correctamente!');


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
        return view('ajusteInventario.show',compact('ajuste_inventario', 'ajuste_inventario_cab'));
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
            $Cantidad_total = 0;
            $sucursal = Auth::user()->empleado->sucursales->first();

            if (!empty('sucursal')) {
                $request['sucursal_id'] = $sucursal->getId();
            }
         
            for ($i=0; $i < collect($request['tab_subtotal'])->count(); $i++){

                $subtotal =  $request['tab_subtotal'][$i];
                $subtotal = str_replace('.', '', $subtotal);
                //$subtotal = str_replace(',', '.', $subtotal);
                
                $cantidad_total = $cantidad_total + $subtotal;
            }

            //pasamos los parámetros del request
            $ajuste_inventario_cab->empleado_id = $request['empleado_id'];
            $ajuste_inventario_cab->fecha_emision = $request['fecha_emision']; 
            $ajuste_inventario_cab->concepto_ajuste_id = $request['concepto_ajuste_id'];       
            $ajuste_inventario_cab->sucursal_id = $request['sucursal_id'];     
            $ajuste_inventario_cab->cantidad_total = $cantidad_total;
            $ajuste_inventario_cab->motivo = $request['motivo'];

            //guardamos los cambios
            $ajuste_inventario->update();

            //borramos el detalle anterior
            $ajuste_inventario->AjusteInventarioDetalle()->delete();

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

                $subtotal = $tab_subtotal[$i];
                $subtotal = str_replace('.', '', $subtotal);
                //$subtotal = str_replace(',', '.', $subtotal);

                //datos del articulo.
                $articulo_aux = Articulo::where('id', $tab_articulo_id[$i])->first();//para traer algunas cosas del maestro    


              
                $ajuste_inventario_det = new AjusteInventarioDet();

                $ajuste_inventario_det->ajuste_inventario_cab_id = $ajuste_inventario->id; 
                $ajuste_inventario_det->articulo_id = $tab_articulo_id[$i];
                $ajuste_inventario_det->cantidad = $cantidad;
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
        $ajuste_inventario = AjusteInventarioCab::findOrFail($id);
        $ajuste_inventario->ajustesDetalle()->delete();
        return AjusteInventarioCab::destroy($id);
    }

    public function apiAjusteInventario()
    {
        $permiso_editar = Auth::user()->can('ajusteInventario.edit');
        $permiso_eliminar = Auth::user()->can('ajusteInventario.destroy');
        $permiso_ver = Auth::user()->can('ajusteInventario.show');
        $ajuste_inventario = AjusteInventarioCab::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                if ($permiso_ver) {
                    return Datatables::of($ajustes_inventarios)
                    ->addColumn('fecha', function($ajuste_inventario){
                        return $ajuste_inventario->getFechaEmision();
                    })
                    ->addColumn('empleado', function($ajuste_inventario){
                        return $ajuste_inventario->cliente->getNombreIndex();
                    })
                    ->addColumn('concepto_ajuste', function($ajuste_inventario){
                        return $ajuste_inventario->concepto_ajuste->getConceptoAjuste();
                    })
                    ->addColumn('cantidad_total', function($ajuste_inventario){
                        return $ajuste_inventario->getCantidadTotal();
                    })

                    ->addColumn('action', function($ajustes_inventarios){
                        return '<a onclick="showForm('. $ajustes_inventarios->id .')" class="btn btn-primary btn-sm" title="Ver Ajuste de inventario"><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $ajuste_inventario->id .')" class="btn btn-warning btn-sm" title="Editar Ajuste de inventario"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a onclick="deleteData('. $ajustes_inventarios->id .')" class="btn btn-danger btn-sm" title="Eliminar Ajuste de inventario"><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                } else{
                    return Datatables::of($ajustes_inventarios)
                    ->addColumn('fecha', function($ajuste_inventario){
                        return $ajuste_inventario->getFechaEmision();
                    })
                    ->addColumn('empleado', function($ajuste_inventario){
                        return $ajuste_inventario->cliente->getNombreIndex();
                    })
                    ->addColumn('concepto_ajuste', function($ajuste_inventario){
                        return $ajuste_inventario->concepto_ajuste->getConceptoAjuste();
                    })
                    ->addColumn('cantidad_total', function($ajuste_inventario){
                        return $ajuste_inventario->getCantidadTotal();
                    })
                    ->addColumn('action', function($ajustes_inventarios){
                        return '<a class="btn btn-primary btn-sm" title="Ver Ajuste de inventario"  disabled><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $ajuste_inventario->id .')" class="btn btn-warning btn-sm" title="Editar Ajuste de inventario"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a onclick="deleteData('. $ajustes_inventarios->id .')" class="btn btn-danger btn-sm" title="Eliminar Ajuste de inventario"><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                }
            } else {
                if ($permiso_ver) {
                    return Datatables::of($ajustes_inventarios)
                    ->addColumn('fecha', function($ajuste_inventario){
                        return $ajuste_inventario->getFechaEmision();
                    })
                    ->addColumn('empleado', function($ajuste_inventario){
                        return $ajuste_inventario->cliente->getNombreIndex();
                    })
                    ->addColumn('concepto_ajuste', function($ajuste_inventario){
                        return $ajuste_inventario->concepto_ajuste->getConceptoAjuste();
                    })
                    ->addColumn('cantidad_total', function($ajuste_inventario){
                        return $ajuste_inventario->getCantidadTotal();
                    })
                    ->addColumn('action', function($ajustes_inventarios){
                        return '<a onclick="showForm('. $ajustes_inventarios->id .')" class="btn btn-primary btn-sm" title="Ver Ajuste de inventario"><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $ajuste_inventario->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                               '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                    })->make(true);
                } else{
                    return Datatables::of($ajustes_inventarios)
                    ->addColumn('action', function($ajustes_inventarios){
                        return '<a class="btn btn-primary btn-sm" title="Ver Ajuste de inventario" disabled><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $ajuste_inventario->id .')" class="btn btn-warning btn-sm" title="Editar Ajuste de inventario"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a class="btn btn-danger btn-sm" title="Eliminar Ajuste de inventario" disabled><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                }
            }
        } elseif ($permiso_eliminar) {
            if ($permiso_ver) {
                return Datatables::of($ajustes_inventarios)

                ->addColumn('fecha', function($ajuste_inventario){
                    return $ajuste_inventario->getFechaEmision();
                })
                ->addColumn('empleado', function($ajuste_inventario){
                    return $ajuste_inventario->cliente->getNombreIndex();
                })
                ->addColumn('concepto_ajuste', function($ajuste_inventario){
                    return $ajuste_inventario->concepto_ajuste->getConceptoAjuste();
                })
                ->addColumn('cantidad_total', function($ajuste_inventario){
                    return $ajuste_inventario->getCantidadTotal();
                })

                ->addColumn('action', function($ajustes_inventarios){
                    return '<a onclick="showForm('. $ajustes_inventarios->id .')" class="btn btn-primary btn-sm" title="Ver Ajuste de inventario"><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $ajustes_inventarios->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else{
                return Datatables::of($ajustes_inventarios)
                ->addColumn('action', function($ajustes_inventarios){
                    return '<a class="btn btn-primary btn-sm" title="Ver Ajuste de inventario" disabled><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $ajustes_inventarios->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } else {
            if ($permiso_ver) {
                return Datatables::of($ajustes_inventarios)
                ->addColumn('fecha', function($ajuste_inventario){
                    return $ajuste_inventario->getFechaEmision();
                })
                ->addColumn('empleado', function($ajuste_inventario){
                    return $ajuste_inventario->cliente->getNombreIndex();
                })
                ->addColumn('concepto_ajuste', function($ajuste_inventario){
                    return $ajuste_inventario->concepto_ajuste->getConceptoAjuste();
                })
                ->addColumn('cantidad_total', function($ajuste_inventario){
                    return $ajuste_inventario->getCantidadTotal();
                })

                ->addColumn('action', function($ajustes_inventarios){
                    return '<a onclick="showForm('. $ajustes_inventarios->id .')" class="btn btn-primary btn-sm" title="Ver Ajuste de inventario"><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else{
                return Datatables::of($ajustes_inventarios)
                ->addColumn('fecha', function($ajuste_inventario){
                    return $ajuste_inventario->getFechaEmision();
                })
                ->addColumn('empleado', function($ajuste_inventario){
                    return $ajuste_inventario->cliente->getNombreIndex();
                })
                ->addColumn('concepto_ajuste', function($ajuste_inventario){
                    return $ajuste_inventario->concepto_ajuste->getConceptoAjuste();
                })
                ->addColumn('cantidad_total', function($ajuste_inventario){
                    return $ajuste_inventario->getCantidadTotal();
                })

                ->addColumn('action', function($ajustes_inventarios){
                    return '<a class="btn btn-primary btn-sm" title="Ver Ajuste de inventario"  disabled><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        }
    }
}
