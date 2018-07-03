<?php

namespace App\Http\Controllers;

use App\Modulo;
use App\Concepto;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class ConceptoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modulos = Modulo::all();
        return view('concepto.index', compact('modulos'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TipoComprobante  $tipoComprobante
     * @return \Illuminate\Http\Response
     */
    public function show(Concepto $concepto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoComprobante  $tipoComprobante
     * @return \Illuminate\Http\Response
     */
    public function edit(Concepto $concepto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoComprobante  $tipoComprobante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Concepto $concepto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TipoComprobante  $tipoComprobante
     * @return \Illuminate\Http\Response
     */
    public function destroy(Concepto $concepto)
    {
        //
    }

    public function apiConceptos()
    {
        $permiso_editar = Auth::user()->can('conceptos.edit');
        $permiso_eliminar = Auth::user()->can('conceptos.destroy');
        $conceptos = Concepto::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($conceptos)
                ->addColumn('modulo', function($conceptos){
                    if (empty($conceptos->modulo)) {
                         return null;
                     } else {
                        return $conceptos->modulo->descripcion;
                    }
                })
                ->addColumn('action', function($conceptos){
                    return '<a onclick="editForm('. $conceptos->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $conceptos->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($conceptos)
                ->addColumn('action', function($conceptos){
                    return '<a onclick="editForm('. $conceptos->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($conceptos)
            ->addColumn('action', function($conceptos){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $conceptos->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($conceptos)
            ->addColumn('action', function($conceptos){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
