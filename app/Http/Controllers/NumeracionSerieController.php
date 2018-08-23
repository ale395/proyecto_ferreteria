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
        $conceptos = Concepto::where('estado', 'A')->get();
        $series = Serie::where('estado', 'A')->get();
        return view('numeracionSerie.index', compact('conceptos', 'series'));
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
            'concepto_id' => $request['concepto_id'],
            'serie_id' => $request['serie_id'],
            'nro_inicial' => $request['nro_inicial'],
            'nro_final' => $request['nro_final'],
            'estado' => $request['estado']
        ];

        $numeracion_serie = NumeracionSerie::create($data);

        return $numeracion_serie;
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
    public function edit($id)
    {
        $numeracion_serie = NumeracionSerie::findOrFail($id);
        return $numeracion_serie;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NumeracionSerie  $numeracionSerie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $numeracion_serie = NumeracionSerie::findOrFail($id);
        $numeracion_serie->concepto_id = $request['concepto_id'];
        $numeracion_serie->serie_id = $request['serie_id'];
        $numeracion_serie->nro_inicial = $request['nro_inicial'];
        $numeracion_serie->nro_final = $request['nro_final'];
        $numeracion_serie->estado = $request['estado'];
        
        $numeracion_serie->update();

        return $numeracion_serie;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NumeracionSerie  $numeracionSerie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return NumeracionSerie::destroy($id);
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
