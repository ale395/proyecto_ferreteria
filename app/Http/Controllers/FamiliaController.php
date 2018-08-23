<?php

namespace App\Http\Controllers;

use Validator;
use App\Familia;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class FamiliaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('familia.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return view('familia.create');
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
            'num_familia' => 'required|max:20|unique:familias,num_familia',
            'descripcion' => 'required|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }


        $data = [
            'num_familia' => $request['num_familia'],
            'descripcion' => $request['descripcion']
        ];

        return Familia::create($data);

    }

    /*
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'num_familia' => 'required|string|max:255|unique:num_familia',
            'descripcion' => 'required|string|max:255',
        ]);
    }
    */

    /**
     * Display the specified resource.
     *
     * @param  \App\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function show(Familia $familia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function edit(Familia $familia)
    {
        return $familia;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Familia $familia)
    {

        $rules = [
            'num_familia' => 'required|max:20|unique:familias,num_familia',
            'descripcion' => 'required|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $familia->num_familia = $request['num_familia'];
        $familia->descripcion = $request['descripcion'];
        
        $familia->update();

        return $familia;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Familia::destroy($id);

    }

    //Función que retorna un JSON con todos los registros para que los maneje AJAX desde el DataTable
    public function apiFamilias()
    {
        $permiso_editar = Auth::user()->can('familias.edit');;
        $permiso_eliminar = Auth::user()->can('familias.destroy');;
        $familia = Familia::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($familia)
                ->addColumn('action', function($familia){
                    return '<a onclick="editForm('. $familia->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $familia->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($familia)
                ->addColumn('action', function($familia){
                    return '<a onclick="editForm('. $familia->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($familia)
            ->addColumn('action', function($familia){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $familia->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($familia)
            ->addColumn('action', function($familia){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
