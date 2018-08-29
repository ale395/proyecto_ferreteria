<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Impuesto;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class ImpuestoController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('impuesto.index');
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
             'codigo' => $request['codigo'],
        'descripcion' => $request['descripcion'],
         'porcentaje' => $request['porcentaje']
        ];

        return Impuesto::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Impuesto  $impuesto
     * @return \Illuminate\Http\Response
     */
    public function show(Impuesto $impuesto)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Impuesto  $impuesto
     * @return \Illuminate\Http\Response
     */
    public function edit(Impuesto $impuesto)
    {
        //dd($impuesto);
        return $impuesto;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Impuesto  $impuesto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Impuesto $impuesto)
    {
             $impuesto->codigo = $request['codigo'];
        $impuesto->descripcion = $request['descripcion'];
         $impuesto->porcentaje = $request['porcentaje'];
        $impuesto->update();

        return $impuesto;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Impuesto  $impuesto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Impuesto::destroy($id);
    }

    //Función que retorna un JSON con todos los módulos para que los maneje AJAX del lado del servidor
    public function apiImpuestos()
    {
        $permiso_editar = Auth::user()->can('impuestos.edit');;
        $permiso_eliminar = Auth::user()->can('impuestos.destroy');;
        $impuesto = Impuesto::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($impuesto)
                ->addColumn('action', function($impuesto){
                    return '<a onclick="editForm('. $impuesto->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $impuesto->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($impuesto)
                ->addColumn('action', function($impuesto){
                    return '<a onclick="editForm('. $impuesto->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($impuesto)
            ->addColumn('action', function($impuesto){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $impuesto->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($impuesto)
            ->addColumn('action', function($impuesto){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
