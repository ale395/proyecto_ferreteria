<?php

namespace App\Http\Controllers;

use App\Concepto;
use App\Serie;
use App\NumeracionSerie;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class NumeracionSerieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conceptos = Concepto::where('estado', 'A');
        $series = Serie::where('estado', 'A');
        return view('numeserie.index', compact('conceptos', 'series'));
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
     * @param  \App\NumeracionSerie  $numeracionSerie
     * @return \Illuminate\Http\Response
     */
    public function show(NumeracionSerie $numeracionSerie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NumeracionSerie  $numeracionSerie
     * @return \Illuminate\Http\Response
     */
    public function edit(NumeracionSerie $numeracionSerie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NumeracionSerie  $numeracionSerie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NumeracionSerie $numeracionSerie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NumeracionSerie  $numeracionSerie
     * @return \Illuminate\Http\Response
     */
    public function destroy(NumeracionSerie $numeracionSerie)
    {
        //
    }

    public function apiNumeSeries()
    {
        $permiso_editar = Auth::user()->can('numeseries.edit');
        $permiso_eliminar = Auth::user()->can('numeseries.destroy');
        $nume_series = NumeracionSerie::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($nume_series)
                ->addColumn('concepto', function($nume_series){
                    if (empty($nume_series->concepto)) {
                         return null;
                     } else {
                        return $nume_series->concepto->concepto;
                    }
                })
                ->addColumn('serie', function($nume_series){
                    if (empty($nume_series->serie)) {
                         return null;
                     } else {
                        return $nume_series->serie->serie;
                    }
                })
                ->addColumn('nomb_estado', function($timbrado){
                    if ($timbrado->estado == 'A') {
                         return 'Activo';
                     }
                     else {
                        return 'Inactivo';
                    }
                })
                ->addColumn('action', function($nume_series){
                    return '<a onclick="editForm('. $nume_series->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $nume_series->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($nume_series)
                ->addColumn('concepto', function($nume_series){
                    if (empty($nume_series->concepto)) {
                         return null;
                     } else {
                        return $nume_series->concepto->concepto;
                    }
                })
                ->addColumn('serie', function($nume_series){
                    if (empty($nume_series->serie)) {
                         return null;
                     } else {
                        return $nume_series->serie->serie;
                    }
                })
                ->addColumn('nomb_estado', function($timbrado){
                    if ($timbrado->estado == 'A') {
                         return 'Activo';
                     }
                     else {
                        return 'Inactivo';
                    }
                })
                ->addColumn('action', function($nume_series){
                    return '<a onclick="editForm('. $nume_series->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($nume_series)
            ->addColumn('concepto', function($nume_series){
                    if (empty($nume_series->concepto)) {
                         return null;
                     } else {
                        return $nume_series->concepto->concepto;
                    }
                })
            ->addColumn('serie', function($nume_series){
                if (empty($nume_series->serie)) {
                    return null;
                } else {
                    return $nume_series->serie->serie;
                }
            })
            ->addColumn('nomb_estado', function($timbrado){
                    if ($timbrado->estado == 'A') {
                         return 'Activo';
                     }
                     else {
                        return 'Inactivo';
                    }
                })
            ->addColumn('action', function($nume_series){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $nume_series->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($nume_series)
            ->addColumn('concepto', function($nume_series){
                    if (empty($nume_series->concepto)) {
                         return null;
                     } else {
                        return $nume_series->concepto->concepto;
                    }
                })
            ->addColumn('serie', function($nume_series){
                if (empty($nume_series->serie)) {
                    return null;
                } else {
                    return $nume_series->serie->serie;
                }
            })
            ->addColumn('nomb_estado', function($timbrado){
                if ($timbrado->estado == 'A') {
                    return 'Activo';
                }
                else {
                    return 'Inactivo';
                }
            })
            ->addColumn('action', function($nume_series){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
