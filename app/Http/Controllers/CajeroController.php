<?php

namespace App\Http\Controllers;

use App\User;
use App\Cajero;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;
class CajeroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = DB::select('select * from users';
        $users = User::all();
        return view('cajero.index', compact('users'));
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
            'num_cajero' => $request['num_cajero'],
            'descripcion' => $request['descripcion'],
            'id_usuario' => $request['id_usuario']
        ];

        return Cajero::create($data);
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
    public function edit(Cajero $cajero)
    {
        return $cajero;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cajero $cajero)
    {
        $cajero->num_cajero = $request['num_cajero'];
        $cajero->descripcion = $request['descripcion'];
        $cajero->id_usuario = $request['id_usuario'];
        
        $cajero->update();

        return $cajero;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Cajero::destroy($id);
    }

    public function apiCajeros()
    {
        $permiso_editar = Auth::user()->can('cajeros.edit');;
        $permiso_eliminar = Auth::user()->can('cajeros.destroy');;
        $cajero = Cajero::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($cajero)
                ->addColumn('usuario', function($cajero){
                    if (empty($cajero->usuario)) {
                         return null;
                     } else {
                        return $cajero->usuario->name;
                    }
                })
                ->addColumn('action', function($cajero){
                    return '<a onclick="editForm('. $cajero->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $cajero->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($cajero)
                ->addColumn('action', function($cajero){
                    return '<a onclick="editForm('. $cajero->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($cajero)
            ->addColumn('action', function($cajero){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $cajero->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($cajero)
            ->addColumn('action', function($cajero){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
