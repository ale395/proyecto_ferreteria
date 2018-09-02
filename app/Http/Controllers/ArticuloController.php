<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Impuesto;
use App\Grupo;
use App\Familia;
use App\Linea;
use App\UnidadMedida;

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
        $grupos = Grupo::all();
        $familias = Familia::all();
        $lineas = Linea::all();
        $unidadesMedidas = UnidadMedida::all();

        return view('articulos.index', 
        compact('impuestos', 'grupos', 'familias', 'lineas','unidadesMedidas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [

            'control_existencia' => 'required',
            'vendible' => 'required',
            'activo' => 'required',
            'impuesto_id' => 'max:20',
            'grupo_id' => 'max:100',
            'familia_id' => 'max:100',
            'linea_id' => 'required',
            'unidad_medida_id' => 'required',
            

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $data = [
            'codigo' => $request['codigo'],
            'descripcion' => $request['descripcion'],
            'codigo_barra' => $request['codigo_barra'],
            'costo' => $request['costo'],
            'impuesto_id' => $request['impuesto_id'],
            'control_existencia' => $request['control_existencia'],
            'vendible' => $request['vendible'],
            'activo' => $request['activo'],
            'grupo_id' => $request['grupo_id'],
            'familia_id' => $request['familia_id'],
            'linea_id' => $request['linea_id'],
            'unidad_medida_id' => $request['unidad_medida_id']

        ];

        return Articulo::create($data);
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
        return $articulo;
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
            'nombre' => 'required|max:100',
            'apellido' => 'max:100',
            'ruc' => 'max:20',
            'nro_documento' => 'unique:articulos,nro_documento,'.$articulo->id,
            'telefono' => 'max:20',
            'direccion' => 'max:100',
            'correo_electronico' => 'max:100',
            'zona_id' => 'required',
            'tipo_articulo_id' => 'required',
            'activo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $articulo->codigo = $request['codigo'];
        $articulo->descripcion = $request['descripcion'];
        $articulo->codigo_barra = $request['codigo_barra'];
        $articulo->costo = $request['costo'];
        $articulo->impuesto_id = $request['impuesto_id'];
        $articulo->grupo_id = $request['grupo_id'];
        $articulo->familia_id = $request['familia_id'];
        $articulo->linea_id = $request['linea_id'];
        $articulo->undad_medida_id = $request['unidad_medida_id'];
        $articulo->control_existencia = $request['control_existencia'];
        $articulo->vendible = $request['vendible'];
        $articulo->activo = $request['activo'];
        $articulo->update();

        return $articulo;
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
