<?php

namespace App\Http\Controllers;

use App\ConceptoAjuste;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class ConceptoAjusteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('conceptoAjuste.index');
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
            'num_concepto' => $request['num_concepto'],
            'descripcion' => $request['descripcion']
        ];

        return ConceptoAjuste::create($data);
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
    public function edit(ConceptoAjuste $concepto)
    {
        return $concepto;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ConceptoAjuste $concepto)
    {
        $concepto->num_concepto = $request['num_concepto'];
        $concepto->descripcion = $request['descripcion'];
        
        $concepto->update();

        return $concepto;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ConceptoAjuste::destroy($id);
    }

    //Función que retorna un JSON con todos los registros para que los maneje AJAX desde el DataTable
    public function apiConceptosAjuste()
    {
        $permiso_editar = Auth::user()->can('conceptoajuste.edit');;
        $permiso_eliminar = Auth::user()->can('conceptoajuste.destroy');;
        $concepto = ConceptoAjuste::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($concepto)
                ->addColumn('action', function($concepto){
                    return '<a onclick="editForm('. $concepto->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $concepto->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($concepto)
                ->addColumn('action', function($concepto){
                    return '<a onclick="editForm('. $concepto->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($concepto)
            ->addColumn('action', function($concepto){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $concepto->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($concepto)
            ->addColumn('action', function($concepto){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }

}
