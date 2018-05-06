<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Linea;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class LineaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('linea.index');

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
        $data = [
            'num_linea' => $request['num_linea'],
            'descripcion' => $request['descripcion']
        ];

        return Linea::create($data);

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
    public function edit(Linea $linea)
    {
        return $linea;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Linea $linea)
    {
        $linea->num_linea = $request['num_linea'];
        $linea->descripcion = $request['descripcion'];
        
        $linea->update();

        return $linea;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Linea::destroy($id);
    }

    //Función que retorna un JSON con todos los registros para que los maneje AJAX desde el DataTable
    public function apiFamilia()
    {
        $permiso_editar = Auth::user()->can('lineas.edit');;
        $permiso_eliminar = Auth::user()->can('lineas.destroy');;
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
