<?php

namespace App\Http\Controllers;

use Validator;
use App\Sucursal;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sucursal.index');
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
            'codigo' => 'required|max:20|unique:sucursales,codigo',
            'nombre' => 'required|max:100',
            'direccion' => 'max:100',
            'codigo_punto_expedicion' => '|bail|required|numeric|digits:3|unique:sucursales,codigo_punto_expedicion',
            'activo' => 'required'
        ];

        $mensajes = [
            'codigo_punto_expedicion.required' => 'El campo Punto de Expedición es obligatorio.',
            'codigo_punto_expedicion.unique' => 'El valor de Punto de Expedición ya existe.',
            'codigo_punto_expedicion.numeric' => 'El valor de Punto de Expedición debe ser un número.',
            'codigo_punto_expedicion.digits' => 'El valor de Punto de Expedición debe tener :digits digitos.',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $data = [
            'codigo' => $request['codigo'],
            'nombre' => $request['nombre'],
            'direccion' => $request['direccion'],
            'codigo_punto_expedicion' => $request['codigo_punto_expedicion'],
            'activo' => $request['activo']
        ];

        return Sucursal::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function show(Sucursal $sucursal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        return $sucursal;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sucursal = Sucursal::findOrFail($id);

        $rules = [
            'codigo' => 'required|max:20|unique:sucursales,codigo,'.$sucursal->id,
            'nombre' => 'required|max:100',
            'direccion' => 'max:100',
            'codigo_punto_expedicion' => '|bail|required|numeric|digits:3|unique:sucursales,codigo_punto_expedicion,'.$sucursal->id,
            'activo' => 'required'
        ];

        $mensajes = [
            'codigo_punto_expedicion.required' => 'El campo Punto de Expedición es obligatorio.',
            'codigo_punto_expedicion.unique' => 'El valor de Punto de Expedición ya existe.',
            'codigo_punto_expedicion.numeric' => 'El valor de Punto de Expedición debe ser un número.',
            'codigo_punto_expedicion.digits' => 'El valor de Punto de Expedición debe tener :digits digitos.',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $sucursal->codigo = $request['codigo'];
        $sucursal->nombre = $request['nombre'];
        $sucursal->direccion = $request['direccion'];
        $sucursal->codigo_punto_expedicion = $request['codigo_punto_expedicion'];
        $sucursal->activo = $request['activo'];
        
        $sucursal->update();

        return $sucursal;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Sucursal::destroy($id);
    }
    public function apiSucursalesBuscador(Request $request){
        $sucursales_array = [];

        if($request->has('q')){
            $search = strtolower($request->q);
            $sucursales = Sucursal::where('nombre', 'ilike', "%$search%")->get();
        } else {
            $sucursales = Sucursal::all();
        }

        foreach ($sucursales as $sucursal) {
            if ($sucursal->getActivo()) {
                $sucursales_array[] = ['id'=> $sucursal->id, 'text'=> $sucursal->nombre];
            }
        }

        return json_encode($sucursales_array);
    }
    
    public function apiSucursales()
    {
        $permiso_editar = Auth::user()->can('sucursales.edit');
        $permiso_eliminar = Auth::user()->can('sucursales.destroy');
        $sucursales = Sucursal::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($sucursales)
                ->addColumn('activo', function($sucursales){
                    if ($sucursales->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($sucursales){
                    return '<a onclick="editForm('. $sucursales->id .')" class="btn btn-warning btn-sm" title="Editar Sucursal"><i class="fa fa-pencil-square-o"></i></a> ' .
                           '<a onclick="deleteData('. $sucursales->id .')" class="btn btn-danger btn-sm" title="Eliminar Sucursal"><i class="fa fa-trash-o"></i></a>';
                })->make(true);
            } else {
                return Datatables::of($sucursales)
                ->addColumn('activo', function($sucursales){
                    if ($sucursales->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($sucursales){
                    return '<a onclick="editForm('. $sucursales->id .')" class="btn btn-warning btn-sm" title="Editar Sucursal"><i class="fa fa-pencil-square-o"></i></a> ' .
                           '<a class="btn btn-danger btn-sm" title="Eliminar Sucursal" disabled><i class="fa fa-trash-o"></i></a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($sucursales)
            ->addColumn('activo', function($sucursales){
                    if ($sucursales->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
            ->addColumn('action', function($sucursales){
                return '<a class="btn btn-warning btn-sm" title="Editar Sucursal" disabled><i class="fa fa-pencil-square-o"></i></a> ' .
                       '<a onclick="deleteData('. $sucursales->id .')" class="btn btn-danger btn-sm" title="Eliminar Sucursal"><i class="fa fa-trash-o"></i></a>';
            })->make(true);
        } else {
            return Datatables::of($sucursales)
            ->addColumn('activo', function($sucursales){
                    if ($sucursales->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
            ->addColumn('action', function($sucursales){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
