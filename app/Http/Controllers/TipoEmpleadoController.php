<?php

namespace App\Http\Controllers;

use Validator;
use App\TipoEmpleado;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class TipoEmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tipoempleado.index');
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
            'codigo' => 'required|max:20|unique:tipos_empleados},codigo',
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

        return TipoEmpleado::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TipoEmpleado  $tipoEmpleado
     * @return \Illuminate\Http\Response
     */
    public function show(TipoEmpleado $tipoEmpleado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoEmpleado  $tipoEmpleado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipo_empleado = TipoEmpleado::findOrFail($id);
        return $tipo_empleado;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoEmpleado  $tipoEmpleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tipo_empleado = TipoEmpleado::findOrFail($id);

        $rules = [
            'codigo' => 'required|max:20|unique:tipos_empleados,codigo,'.$tipo_empleado->id,
            'nombre' => 'required|max:100'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $tipo_empleado->codigo = $request['codigo'];
        $tipo_empleado->nombre = $request['nombre'];
        
        $tipo_empleado->update();

        return $tipo_empleado;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TipoEmpleado  $tipoEmpleado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return TipoEmpleado::destroy($id);
    }

    public function apiTiposEmpleados()
    {
        $permiso_editar = Auth::user()->can('tiposEmpleados.edit');
        $permiso_eliminar = Auth::user()->can('tiposEmpleados.destroy');
        $tipos_empleados = TipoEmpleado::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($tipos_empleados)
                ->addColumn('action', function($tipos_empleados){
                    return '<a onclick="editForm('. $tipos_empleados->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $tipos_empleados->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($tipos_empleados)
                ->addColumn('action', function($tipos_empleados){
                    return '<a onclick="editForm('. $tipos_empleados->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($tipos_empleados)
            ->addColumn('action', function($tipos_empleados){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $tipos_empleados->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($tipos_empleados)
            ->addColumn('action', function($tipos_empleados){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
