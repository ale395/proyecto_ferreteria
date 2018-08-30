<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Validator;
use App\TipoProveedor;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class TipoProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tiposproveedores.index');
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
            'codigo' => 'required|max:20|unique:tipo_proveedores,codigo',
            'nombre' => 'required|max:100'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $data = [
            'codigo' => $request['codigo'],
            'nombre' => $request['nombre']
        ];

        return TipoProveedor::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipo_proveedor = TipoProveedor::FindOrFail($id);
        return $tipo_proveedor;
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
        $tipo_proveedor = TipoProveedor::findOrFail($id);

        $rules = [
            'codigo' => 'required|max:20|unique:tipo_proveedores,codigo,'.$tipo_proveedor->id,
            'nombre' => 'required|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $tipo_proveedor->codigo = $request['codigo'];
        $tipo_proveedor->nombre = $request['nombre'];
        
        $tipo_proveedor->update();

        return $tipo_proveedor;    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return TipoProveedor::destroy($id);
    }

    public function apiTipoProveedor()
    {
        $permiso_editar = Auth::user()->can('tiposproveedores.edit');
        $permiso_eliminar = Auth::user()->can('tiposproveedores.destroy');
        $tipo_proveedor = TipoProveedor::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($tipo_proveedor)
                ->addColumn('action', function($tipo_proveedor){
                    return '<a onclick="editForm('. $tipo_proveedor->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $tipo_proveedor->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($tipo_proveedor)
                ->addColumn('action', function($tipo_proveedor){
                    return '<a onclick="editForm('. $tipo_proveedor->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($tipo_proveedor)
            ->addColumn('action', function($tipo_proveedor){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $tipo_proveedor->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($tipo_proveedor)
            ->addColumn('action', function($tipo_proveedor){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
