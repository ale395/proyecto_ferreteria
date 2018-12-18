<?php

namespace App\Http\Controllers;

use Image;
use Validator;
use App\Articulo;
use App\Sucursal;
use App\Impuesto;
use App\Rubro;
use App\Familia;
use App\Linea;
use App\UnidadMedida;
use App\ListaPrecioDetalle;
use App\ExistenciaArticulo;

use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class ArticuloController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $impuestos = Impuesto::all();
        $rubros = Rubro::all();
        $familias = Familia::all();
        $lineas = Linea::all();
        $unidadesMedidas = UnidadMedida::all();

        return view('articulo.index', 
        compact('impuestos', 'rubros', 'familias', 'lineas','unidadesMedidas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $impuestos = Impuesto::all();
        $rubros = Rubro::all();
        $familias = Familia::all();
        $lineas = Linea::all();
        $unidadesMedidas = UnidadMedida::all();

        return view('articulo.create', 
        compact('impuestos', 'rubros', 'familias', 'lineas','unidadesMedidas'));
  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $articulo = new Articulo();
        $rules = [

            'control_existencia' => 'required',
            'vendible' => 'required',
            'activo' => 'required',
            'impuesto_id' => 'max:20',
            'rubro_id' => 'max:100',
            'familia_id' => 'max:100',
            'linea_id' => 'required',
            'unidad_medida_id' => 'required',
            

        ];

        $validator = Validator::make($request->all(), $rules);
       
        if($request->hasFile('img_producto')){
       
            $img_producto = $request->file('img_producto');
            $filename = $request['descripcion']/*.'-'.time()*/.'.'.$img_producto->getClientOriginalExtension();
            Image::make($img_producto)->resize(300, 300)->save( public_path('/images/articulos/' . $filename ) );
            $articulo->img_producto = $filename;
                  
        }

        $articulo->setCodigo ($request['codigo']);
        $articulo->setDescripcion($request['descripcion']);
        $articulo->setCodigoBarra($request['codigo_barra']);
        $articulo->setUltimoCosto($request['ultimo_costo']);
        $articulo->setCostoPromedio($request['costo_promedio']);
        $articulo->setPorcentajeGanancia($request['porcentaje_ganancia']);
        $articulo->setControExistencia($request['control_existencia']);
        $articulo->setVendible($request['vendible']);
        $articulo->setActivo($request['activo']);
        $articulo->setImpuestoId($request['impuesto_id']);
        $articulo->setRubroId($request['rubro_id']);
        $articulo->setFamiliaId($request['familia_id']);
        $articulo->setLineaId($request['linea_id']);
        $articulo->setUnidadMedidaId($request['unidad_medida_id']);
        $articulo->setComentario ( $request['comentario']);
        
        $articulo->save();



        return redirect('/articulos')->with('status', 'Datos guardados correctamente!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $articulo = Articulo::findOrFail($id);
        return $articulo;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $articulo = Articulo::findOrFail($id);
        $impuestos = Impuesto::all();
        $rubros = Rubro::all();
        $familias = Familia::all();
        $lineas = Linea::all();
        $unidadesMedidas = UnidadMedida::all();
        return view('articulo.edit', 
        compact('impuestos', 'rubros', 'familias', 'lineas','unidadesMedidas', 'articulo'));
  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $articulo = Articulo::findOrFail($id);

        $rules = [
            'codigo' => 'required|max:20|unique:articulos,codigo,'.$articulo->id,
            'control_existencia' => 'required',
            'vendible' => 'required',
            'activo' => 'required',
            'impuesto_id' => 'max:20',
            'rubro_id' => 'max:100',
            'familia_id' => 'max:100',
            'linea_id' => 'required',
            'unidad_medida_id' => 'required',
            
        ];

        $validator = Validator::make($request->all(), $rules);

        if($request->hasFile('img_producto')){
            
            $img_producto = $request->file('img_producto');
            $filename = $request['descripcion']/*.'-'.time()*/.'.'.$img_producto->getClientOriginalExtension();
            Image::make($img_producto)->resize(300, 300)->save( public_path('/images/articulos/' . $filename ) );
            $articulo->img_producto = $filename;
            
        }

        $articulo->setCodigo ($request['codigo']);
        $articulo->setDescripcion($request['descripcion']);
        $articulo->setCodigoBarra($request['codigo_barra']);
        $articulo->setUltimoCosto($request['ultimo_costo']);
        $articulo->setCostoPromedio($request['costo_promedio']);
        $articulo->setPorcentajeGanancia($request['porcentaje_ganancia']);
        $articulo->setControExistencia($request['control_existencia']);
        $articulo->setVendible($request['vendible']);
        $articulo->setActivo($request['activo']);
        $articulo->setImpuestoId($request['impuesto_id']);
        $articulo->setRubroId($request['rubro_id']);
        $articulo->setFamiliaId($request['familia_id']);
        $articulo->setLineaId($request['linea_id']);
        $articulo->setUnidadMedidaId($request['unidad_medida_id']);
        $articulo->setComentario ( $request['comentario']);

        $articulo->update();
        

        return redirect('/articulos')->with('status', 'Datos guardados correctamente!');



        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Articulo::destroy($id);
    }

    public function apiArticulosVentas(Request $request){
        $articulos_array = [];

        if($request->has('q')){
            $search = strtolower($request->q);
            $articulos = Articulo::where('descripcion', 'ilike', "%$search%")
                ->orWhere('codigo', 'ilike', "%$search%")
                ->orWhere('codigo_barra', 'ilike', "%$search%")
                ->get();
        } else {
            $articulos = Articulo::all();
        }

        foreach ($articulos as $articulo) {
            if ($articulo->getActivo() && $articulo->getVendible()) {
                $articulos_array[] = array('id'=> $articulo->getId(), 'text'=> $articulo->getNombreSelect());
            }
        }

        return json_encode($articulos_array);
    }

    public function apiArticulosCotizacion($articulo_id, $lista_precio_id){
        if (!empty($articulo_id) && !empty($lista_precio_id)) {
            $articulo = collect(Articulo::findOrFail($articulo_id));
            $articulo_obj = Articulo::findOrFail($articulo_id);
            $precio = ListaPrecioDetalle::where('lista_precio_id', $lista_precio_id)
                ->where('articulo_id', $articulo_id)
                ->where('fecha_vigencia', '<=', today())->get();
            $precio = $precio->sortByDesc('fecha_vigencia');
            $precio = $precio->first();
            $sucursal = Auth::user()->empleado->sucursales->first();
            $existencia = ExistenciaArticulo::where('articulo_id', $articulo_obj->id)
                ->where('sucursal_id', $sucursal->id)->first();
            
            if (empty($precio)) {
                $articulo->put('precio', 0);
            } else {
                $articulo->put('precio', $precio->precio);
            }

            if (empty($existencia)) {
                $articulo->put('existencia', 0);
            } else {
                $articulo->put('existencia', $existencia->getCantidad());
            }
            $articulo->put('iva', $articulo_obj->impuesto);
            return $articulo;
        };
    }

    public function apiArticulosExistencia($articulo_id, $sucursal_id){
        $articulo_obj = Articulo::findOrFail($articulo_id);
        $sucursal_obj = Sucursal::findOrFail($sucursal_id);

        $existencia = ExistenciaArticulo::where('articulo_id', $articulo_obj->id)
        ->where('sucursal_id', $sucursal_obj->id)->first();
            if (empty($existencia)) {
               // $articulo_obj->put('existencia', 0);
            } else {
              //  $articulo_obj->put('existencia', $existencia->getCantidad());
                return ($existencia);
            }
    }

    public function apiArticulosCosto($articulo_id){

        if (!empty($articulo_id)) {
            $articulo = collect(Articulo::findOrFail($articulo_id));
            $articulo_obj = Articulo::findOrFail($articulo_id);
            //$articulo = Articulo::findOrFail($articulo_id)->first();
            //$ultimo_costo = $articulo->ultimo_costo;    
            
            $articulo->put('iva', $articulo_obj->impuesto);
            return $articulo;
        };
    }

    public function apiArticulos()
    {
        $permiso_editar = Auth::user()->can('articulos.edit');
        $permiso_eliminar = Auth::user()->can('articulos.destroy');
        $permiso_ver = Auth::user()->can('articulos.show');
        $articulos = Articulo::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                if ($permiso_ver) {
                    return Datatables::of($articulos)
                    ->addColumn('vendible', function($articulos){
                        if ($articulos->vendible) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('activo', function($articulos){
                        if ($articulos->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($articulos){
                        return '<a onclick="showForm('. $articulos->id .')" class="btn btn-primary btn-sm" title="Ver Articulo"><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $articulos->id .')" class="btn btn-warning btn-sm" title="Editar Articulo"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a onclick="deleteData('. $articulos->id .')" class="btn btn-danger btn-sm" title="Eliminar Articulo"><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                } else{
                    return Datatables::of($articulos)
                    ->addColumn('vendible', function($articulos){
                        if ($articulos->vendible) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('activo', function($articulos){
                        if ($articulos->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($articulos){
                        return '<a class="btn btn-primary btn-sm" title="Ver Articulo"  disabled><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $articulos->id .')" class="btn btn-warning btn-sm" title="Editar Articulo"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a onclick="deleteData('. $articulos->id .')" class="btn btn-danger btn-sm" title="Eliminar Articulo"><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                }
            } else {
                if ($permiso_ver) {
                    return Datatables::of($articulos)
                    ->addColumn('vendible', function($articulos){
                        if ($articulos->vendible) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('activo', function($articulos){
                        if ($articulos->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($articulos){
                        return '<a onclick="showForm('. $articulos->id .')" class="btn btn-primary btn-sm" title="Ver Articulo"><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $articulos->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                               '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                    })->make(true);
                } else{
                    return Datatables::of($articulos)
                    ->addColumn('vendible', function($articulos){
                        if ($articulos->vendible) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('activo', function($articulos){
                        if ($articulos->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($articulos){
                        return '<a class="btn btn-primary btn-sm" title="Ver Articulo" disabled><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $articulos->id .')" class="btn btn-warning btn-sm" title="Editar Articulo"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a class="btn btn-danger btn-sm" title="Eliminar Articulo" disabled><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                }
            }
        } elseif ($permiso_eliminar) {
            if ($permiso_ver) {
                return Datatables::of($articulos)
                ->addColumn('vendible', function($articulos){
                    if ($articulos->vendible) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('activo', function($articulos){
                        if ($articulos->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                ->addColumn('action', function($articulos){
                    return '<a onclick="showForm('. $articulos->id .')" class="btn btn-primary btn-sm" title="Ver Articulo"><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $articulos->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else{
                return Datatables::of($articulos)
                ->addColumn('vendible', function($articulos){
                    if ($articulos->vendible) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('activo', function($articulos){
                        if ($articulos->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                ->addColumn('action', function($articulos){
                    return '<a class="btn btn-primary btn-sm" title="Ver Articulo" disabled><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $articulos->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } else {
            if ($permiso_ver) {
                return Datatables::of($articulos)
                ->addColumn('vendible', function($articulos){
                    if ($articulos->vendible) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('activo', function($articulos){
                        if ($articulos->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                ->addColumn('action', function($articulos){
                    return '<a onclick="showForm('. $articulos->id .')" class="btn btn-primary btn-sm" title="Ver Articulo"><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else{
                return Datatables::of($articulos)
                ->addColumn('vendible', function($articulos){
                    if ($articulos->vendible) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('activo', function($articulos){
                        if ($articulos->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                ->addColumn('action', function($articulos){
                    return '<a class="btn btn-primary btn-sm" title="Ver Articulo"  disabled><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        }
    }
}
