<?php

namespace App\Http\Controllers;


use Validator;
use App\Deposito;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class DepositoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('deposito.index');
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
             'activo' => $request['activo']
        
        ];

        return Deposito::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deposito  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function show(Deposito $deposito)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deposito  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Deposito $deposito)
    {
        //dd($deposito);
        return $deposito;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deposito  $deposito
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deposito $deposito)
    {
             $deposito->codigo = $request['codigo'];
        $deposito->descripcion = $request['descripcion'];
             $deposito->activo = $request['activo'];
        $deposito->update();

        return $deposito;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deposito  $deposito
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Deposito::destroy($id);
    }

    //Función que retorna un JSON con todos los módulos para que los maneje AJAX del lado del servidor
    public function apiDepositos()
    {
        $permiso_editar = Auth::user()->can('depositos.edit');;
        $permiso_eliminar = Auth::user()->can('depositos.destroy');;
        $deposito = Deposito::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($deposito)
                ->addColumn('activo', function($deposito){

                    if ($deposito->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($deposito){

                    return '<a onclick="editForm('. $deposito->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $deposito->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($deposito)
                ->addColumn('activo', function($deposito){

                    if ($deposito->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($deposito){

                    return '<a onclick="editForm('. $deposito->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($deposito)
            ->addColumn('activo', function($deposito){

                if ($deposito->activo) {
                    return 'Si';
                }else{
                    return 'No';
                }
            })
            ->addColumn('action', function($deposito){

                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $deposito->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($deposito)
            ->addColumn('activo', function($deposito){

                if ($deposito->activo) {
                    return 'Si';
                }else{
                    return 'No';
                }
            })
            ->addColumn('action', function($deposito){

                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}

