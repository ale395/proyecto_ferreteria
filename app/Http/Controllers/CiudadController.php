<?php

namespace App\Http\Controllers;

use App\Pais;
use App\Ciudad;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class CiudadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paises = Pais::all();
        return view('ciudad.index', compact('paises'));
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

        return Ciudad::create($data);
    }

    public function show(Ciudad $ciudad)
    {
        //
    }

    public function edit(Ciudad $ciudad)
    {
        return $ciudad;
    }

    public function destroy($id)
    {
        return Ciudad::destroy($id);
    }

    public function update(Request $request, Ciudad $ciudad)
    {

        $ciudad->descripcion = $request['descripcion'];
        $ciudad->pais_id = $request['pais_id'];
        
        $ciudad->update();

        return $ciudad;
    }
    public function apiCiudades()
    {
        $permiso_editar = Auth::user()->can('ciudades.edit');;
        $permiso_eliminar = Auth::user()->can('ciudades.destroy');;
        $ciudad = Ciudad::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($ciudad)
                ->addColumn('pais', function($ciudad){
                    if (empty($pais->ciudad)) {
                         return null;
                     } else {
                        return $ciudad->pais->descripcion;
                    }
                })
                ->addColumn('action', function($ciudad){
                    return '<a onclick="editForm('. $ciudad->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $ciudad->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($ciudad)
                ->addColumn('action', function($ciudad){
                    return '<a onclick="editForm('. $ciudad->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($ciudad)
            ->addColumn('action', function($ciudad){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $ciudad->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($ciudad)
            ->addColumn('action', function($ciudad){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
