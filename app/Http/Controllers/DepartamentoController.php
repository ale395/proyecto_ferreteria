<?php

namespace App\Http\Controllers;

use App\Pais;
use App\Departamento;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paises = Pais::all();
        return view('departamento.index', compact('paises'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = [
            'descripcion' => $request['descripcion'],
            'pais_id' => $request['pais_id']
        ];

        return Departamento::create($data);
    }

    public function show(Departamento $departamento)
    {
        //
    }

    public function edit(Departamento $departamento)
    {
        return $departamento;
    }

    public function destroy($id)
    {
        return Departamento::destroy($id);
    }

    public function update(Request $request, Departamento $departamento)
    {

        $departamento->descripcion = $request['descripcion'];
        $departamento->pais_id = $request['pais_id'];
        
        $departamento->update();

        return $departamento;
    }
    public function apiDepartamento()
    {
        $permiso_editar = Auth::user()->can('departamentos.edit');;
        $permiso_eliminar = Auth::user()->can('departamentos.destroy');;
        $departamento = Departamento::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($departamento)
                ->addColumn('pais', function($departamento){
                    if (empty($pais->departamento)) {
                         return null;
                     } else {
                        return $departamento->pais->descripcion;
                    }
                })
                ->addColumn('action', function($departamento){
                    return '<a onclick="editForm('. $departamento->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $departamento->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($departamento)
                ->addColumn('action', function($departamento){
                    return '<a onclick="editForm('. $departamento->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($departamento)
            ->addColumn('action', function($departamento){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $departamento->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($departamento)
            ->addColumn('action', function($departamento){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
