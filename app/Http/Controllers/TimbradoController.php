<?php

namespace App\Http\Controllers;

use App\Timbrado;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class TimbradoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('timbrado.index');
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
            'nro_timbrado' => $request['nro_timbrado'],
            'fecha_vigencia' => $request['fecha_vigencia'],
            'estado' => $request['estado']
        ];

        $timbrado = Timbrado::create($data);

        return $timbrado;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Timbrado  $timbrado
     * @return \Illuminate\Http\Response
     */
    public function show(Timbrado $timbrado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Timbrado  $timbrado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $timbrado = Timbrado::findOrFail($id);
        return $timbrado;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Timbrado  $timbrado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $timbrado = Timbrado::findOrFail($id);
        $timbrado->nro_timbrado = $request['nro_timbrado'];
        $timbrado->fecha_vigencia = $request['fecha_vigencia'];
        $timbrado->estado = $request['estado'];
        
        $timbrado->update();

        return $timbrado;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Timbrado  $timbrado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Timbrado::destroy($id);
    }

    public function apiTimbrados()
    {
        $permiso_editar = Auth::user()->can('timbrados.edit');;
        $permiso_eliminar = Auth::user()->can('timbrados.destroy');;
        $timbrado = Timbrado::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($timbrado)
                ->addColumn('nomb_estado', function($timbrado){
                    if ($timbrado->estado == 'A') {
                         return 'Activo';
                     }
                     else {
                        return 'Inactivo';
                    }
                })
                ->addColumn('action', function($timbrado){
                    return '<a onclick="editForm('. $timbrado->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $timbrado->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($timbrado)
                ->addColumn('nomb_estado', function($timbrado){
                    if ($timbrado->estado == 'A') {
                         return 'Activo';
                     }
                     else {
                        return 'Inactivo';
                    }
                })
                ->addColumn('action', function($timbrado){
                    return '<a onclick="editForm('. $timbrado->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($timbrado)
            ->addColumn('nomb_estado', function($timbrado){
                    if ($timbrado->estado == 'A') {
                         return 'Activo';
                     }
                     else {
                        return 'Inactivo';
                    }
                })
            ->addColumn('action', function($role){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $timbrado->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($timbrado)
            ->addColumn('nomb_estado', function($timbrado){
                    if ($timbrado->estado == 'A') {
                         return 'Activo';
                     }
                     else {
                        return 'Inactivo';
                    }
                })
            ->addColumn('action', function($timbrado){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }

}
