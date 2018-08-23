<?php

namespace App\Http\Controllers;

use App\UnidadMedida; 
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class UnidadMedidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('unidadMedida.index');
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
            'num_umedida' => $request['num_umedida'],
            'descripcion' => $request['descripcion']
        ];

        return UnidadMedida::create($data);
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
    public function edit(UnidadMedida $unidadmedida)
    {
        return $unidadmedida;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UnidadMedida $unidadmedida)
    {
        $unidadmedida->num_umedida = $request['num_umedida'];
        $unidadmedida->descripcion = $request['descripcion'];
        
        $unidadmedida->update();

        return $unidadmedida;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return UnidadMedida::destroy($id);
    }


        //Función que retorna un JSON con todos los registros para que los maneje AJAX desde el DataTable
    public function apiUnidadesMedidas()
    {
        $permiso_editar = Auth::user()->can('unidadesMedidas.edit');;
        $permiso_eliminar = Auth::user()->can('unidadesMedidas.destroy');;
        $unidad_medida = UnidadMedida::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($unidad_medida)
                ->addColumn('action', function($unidad_medida){
                    return '<a onclick="editForm('. $unidad_medida->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $unidad_medida->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($unidad_medida)
                ->addColumn('action', function($unidad_medida){
                    return '<a onclick="editForm('. $unidad_medida->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($unidad_medida)
            ->addColumn('action', function($unidad_medida){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $unidad_medida->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($unidad_medida)
            ->addColumn('action', function($unidad_medida){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }

}
