<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\TipoProveedor;
use App\Proveedor;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipos_proveedores = TipoProveedor::all();
        return view('proveedores.index', compact('tipos_proveedores'));
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
        if (!empty($request['nro_documento'])) {
            $request['nro_documento'] = (integer) str_replace('.', '',$request['nro_documento']);
        }
        
        if (!empty($request['telefono'])) {
            $request['telefono'] = (integer)str_replace(" ","",str_replace(")","",str_replace("(","",str_replace("-","",$request['telefono']))));
        }

        $rules = [
            'codigo' => 'required|max:20|unique:proveedores,codigo',
            'nombre' => 'required|max:100',
            'razon_social' => 'max:100',
            'ruc' => 'max:20|unique:proveedores,codigo',
            'nro_documento' => 'unique:proveedores,nro_documento',
            'telefono' => 'max:20',
            'direccion' => 'max:100',
            'correo_electronico' => 'max:30',
            'activo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $data = [
            'codigo' => $request['codigo'],
            'nombre' => $request['nombre'],
            'razon_social' => $request['razon_social'],
            'ruc' => $request['ruc'],
            'nro_documento' => $request['nro_documento'],
            'telefono' => $request['telefono'],
            'direccion' => $request['direccion'],
            'correo_electronico' => $request['correo_electronico'],
            'tipo_proveedor_id' => $request['tipo_proveedor_id'],
            'activo' => $request['activo']
        ];

        return Proveedor::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return $proveedor; 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return $proveedor; 
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
        $proveedor = Proveedor::findOrFail($id);

        $rules = [
            'codigo' => 'required|max:20|unique:proveedores,codigo,'.$proveedor->id,
            'nombre' => 'required|max:100',
            'razon_social' => 'max:100',
            'ruc' => 'max:20|unique:proveedores,codigo,'.$proveedor->id,
            'nro_documento' => 'unique:proveedores,nro_documento,'.$proveedor->id,
            'telefono' => 'max:20',
            'direccion' => 'max:100',
            'correo_electronico' => 'max:30',
            'activo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $proveedor->codigo = $request['codigo'];
        $proveedor->nombre = $request['nombre'];
        $proveedor->razon_social = $request['razon_social'];
        $proveedor->ruc = $request['ruc'];
        $proveedor->nro_documento = $request['nro_documento'];
        $proveedor->telefono = $request['telefono'];
        $proveedor->direccion = $request['direccion'];
        $proveedor->correo_electronico = $request['correo_electronico'];
        $proveedor->tipo_proveedor_id = $request['tipo_proveedor_id'];
        $proveedor->activo = $request['activo'];
        
        $proveedor->update();

        return $proveedor;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Proveedor::destroy($id);
    }

    public function apiProveedores()
    {
        $permiso_editar = Auth::user()->can('proveedores.edit');
        $permiso_eliminar = Auth::user()->can('proveedores.destroy');
        $permiso_ver = Auth::user()->can('proveedores.show');
        $proveedores = Proveedor::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                if ($permiso_ver) {
                    return Datatables::of($proveedores)
                    ->addColumn('activo', function($proveedores){
                        if ($proveedores->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($proveedores){
                        return '<a onclick="showForm('. $proveedores->id .')" class="btn btn-primary btn-sm" title="Ver Cliente"><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $proveedores->id .')" class="btn btn-warning btn-sm" title="Editar Cliente"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a onclick="deleteData('. $proveedores->id .')" class="btn btn-danger btn-sm" title="Eliminar Cliente"><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                } else{
                    return Datatables::of($proveedores)
                    ->addColumn('activo', function($proveedores){
                        if ($proveedores->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($proveedores){
                        return '<a class="btn btn-primary btn-sm" title="Ver Cliente"  disabled><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $proveedores->id .')" class="btn btn-warning btn-sm" title="Editar Cliente"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a onclick="deleteData('. $proveedores->id .')" class="btn btn-danger btn-sm" title="Eliminar Cliente"><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                }
            } else {
                if ($permiso_ver) {
                    return Datatables::of($proveedores)
                    ->addColumn('activo', function($proveedores){
                        if ($proveedores->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($proveedores){
                        return '<a onclick="showForm('. $proveedores->id .')" class="btn btn-primary btn-sm" title="Ver Cliente"><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $proveedores->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                               '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                    })->make(true);
                } else{
                    return Datatables::of($proveedores)
                    ->addColumn('activo', function($proveedores){
                        if ($proveedores->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($proveedores){
                        return '<a class="btn btn-primary btn-sm" title="Ver Cliente" disabled><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $proveedores->id .')" class="btn btn-warning btn-sm" title="Editar Cliente"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a class="btn btn-danger btn-sm" title="Eliminar Cliente" disabled><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                }
            }
        } elseif ($permiso_eliminar) {
            if ($permiso_ver) {
                return Datatables::of($proveedores)
                ->addColumn('activo', function($proveedores){
                        if ($proveedores->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                ->addColumn('action', function($proveedores){
                    return '<a onclick="showForm('. $proveedores->id .')" class="btn btn-primary btn-sm" title="Ver Cliente"><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $proveedores->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else{
                return Datatables::of($proveedores)
                ->addColumn('activo', function($proveedores){
                        if ($proveedores->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                ->addColumn('action', function($proveedores){
                    return '<a class="btn btn-primary btn-sm" title="Ver Cliente" disabled><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $proveedores->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } else {
            if ($permiso_ver) {
                return Datatables::of($proveedores)
                ->addColumn('activo', function($proveedores){
                        if ($proveedores->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                ->addColumn('action', function($proveedores){
                    return '<a onclick="showForm('. $proveedores->id .')" class="btn btn-primary btn-sm" title="Ver Cliente"><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else{
                return Datatables::of($proveedores)
                ->addColumn('activo', function($proveedores){
                        if ($proveedores->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                ->addColumn('action', function($proveedores){
                    return '<a class="btn btn-primary btn-sm" title="Ver Cliente"  disabled><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        }
    }
}
