<?php

namespace App\Http\Controllers;

use App\ClasificacionCliente;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class ClasificacionClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('clasificacioncliente.index');
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
            'nombre' => $request['nombre']
        ];

        return ClasificacionCliente::create($data);
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
        $clasificacion_cliente = ClasificacionCliente::findOrFail($id);
        return $clasificacion_cliente;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClasificacionCliente $clasificacioncliente)
    {
        $clasificacioncliente->codigo = $request['codigo'];
        $clasificacioncliente->nombre = $request['nombre'];
        
        $clasificacioncliente->update();

        return $clasificacioncliente;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ClasificacionCliente::destroy($id);
    }

      //Función que retorna un JSON con todos los registros para que los maneje AJAX desde el DataTable
    public function apiClasifClientes()
    {
        $permiso_editar = Auth::user()->can('clasificacioncliente.edit');;
        $permiso_eliminar = Auth::user()->can('clasificacioncliente.destroy');;
        $clasificacion_cliente = ClasificacionCliente::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($clasificacion_cliente)
                ->addColumn('action', function($clasificacion_cliente){
                    return '<a onclick="editForm('. $clasificacion_cliente->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $clasificacion_cliente->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($clasificacion_cliente)
                ->addColumn('action', function($clasificacion_cliente){
                    return '<a onclick="editForm('. $clasificacion_cliente->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($clasificacion_cliente)
            ->addColumn('action', function($clasificacion_cliente){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $clasificacion_cliente->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($clasificacion_cliente)
            ->addColumn('action', function($clasificacion_cliente){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }

}
