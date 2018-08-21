<?php

namespace App\Http\Controllers;

use Validator;
use App\Serie;
use App\Timbrado;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class SerieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timbrados = Timbrado::all();
        return view('serie.index', compact('timbrados'));
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
        $rules = [
            'tipo_comprobante' => 'required',
            'serie' => 'required|max:6',
            'timbrado_id' => 'required',
            'nro_inicial' => 'required',
            'nro_final' => 'required',
            'activo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $data = [
            'tipo_comprobante' => $request['tipo_comprobante'],
            'serie' => $request['serie'],
            'timbrado_id' => $request['timbrado_id'],
            'nro_inicial' => $request['nro_inicial'],
            'nro_final' => $request['nro_final'],
            'activo' => $request['activo']
        ];

        return Serie::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function show(Serie $serie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $serie = Serie::findOrFail($id);
        return $serie;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $serie = Serie::findOrFail($id);

        $rules = [
            'tipo_comprobante' => 'required',
            'serie' => 'required|max:6',
            'timbrado_id' => 'required',
            'nro_inicial' => 'required|min:1',
            'nro_final' => 'required',
            'activo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $serie->tipo_comprobante = $request['tipo_comprobante'];
        $serie->serie = $request['serie'];
        $serie->timbrado_id = $request['timbrado_id'];
        $serie->nro_inicial = $request['nro_inicial'];
        $serie->nro_final = $request['nro_final'];
        $serie->activo = $request['activo'];
        
        $serie->update();

        return $serie;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Serie::destroy($id);
    }

    public function apiSeries()
    {
        $permiso_editar = Auth::user()->can('series.edit');
        $permiso_eliminar = Auth::user()->can('series.destroy');
        $series = Serie::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($series)
                ->addColumn('nro_timbrado', function($series){
                    if (empty($series->timbrado)) {
                         return null;
                     } else {
                        return $series->timbrado->nro_timbrado;
                    }
                })
                ->addColumn('tipo_comp', function($series){
                    if ($series->tipo_comprobante == 'F') {
                         return 'Factura';
                     } else {
                        return 'Nota de Crédito';
                    }
                })
                ->addColumn('activo', function($series){
                    if ($series->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($series){
                    return '<a onclick="addVendedor('. $series->id .')" class="btn btn-info btn-sm" title="Asignar Vendedor"><i class="fa fa-share-square-o" aria-hidden="true"></i></a> ' .'<a onclick="editForm('. $series->id .')" class="btn btn-warning btn-sm" title="Editar Serie"><i class="fa fa-pencil-square-o"></i></a> ' .
                           '<a onclick="deleteData('. $series->id .')" class="btn btn-danger btn-sm" title="Eliminar Serie"><i class="fa fa-trash-o"></i></a>';
                })->make(true);
            } else {
                return Datatables::of($series)
                ->addColumn('nro_timbrado', function($series){
                    if (empty($series->timbrado)) {
                         return null;
                     } else {
                        return $series->timbrado->nro_timbrado;
                    }
                })
                ->addColumn('tipo_comp', function($series){
                    if ($series->tipo_comprobante == 'F') {
                         return 'Factura';
                     } else {
                        return 'Nota de Crédito';
                    }
                })
                ->addColumn('activo', function($series){
                    if ($series->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($series){
                    return '<a onclick="editForm('. $series->id .')" class="btn btn-warning btn-sm" title="Editar Serie"><i class="fa fa-pencil-square-o"></i></a> ' .
                           '<a class="btn btn-danger btn-sm" disabled title="Eliminar Serie"><i class="fa fa-trash-o"></i></a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($series)
            ->addColumn('nro_timbrado', function($series){
                if (empty($series->timbrado)) {
                    return null;
                } else {
                    return $series->timbrado->nro_timbrado;
                }
            })
            ->addColumn('tipo_comp', function($series){
                if ($series->tipo_comprobante == 'F') {
                    return 'Factura';
                } else {
                    return 'Nota de Crédito';
                }
            })
            ->addColumn('activo', function($series){
                if ($series->activo) {
                    return 'Si';
                }else{
                    return 'No';
                }
            })
            ->addColumn('action', function($series){
                return '<a class="btn btn-warning btn-sm" disabled title="Editar Serie"><i class="fa fa-pencil-square-o"></i></a> ' .
                       '<a onclick="deleteData('. $series->id .')" class="btn btn-danger btn-sm" title="Eliminar Serie"><i class="fa fa-trash-o"></i></a>';
            })->make(true);
        } else {
            return Datatables::of($series)
            ->addColumn('nro_timbrado', function($series){
                if (empty($series->timbrado)) {
                    return null;
                } else {
                    return $series->timbrado->nro_timbrado;
                }
            })
            ->addColumn('tipo_comp', function($series){
                if ($series->tipo_comprobante == 'F') {
                    return 'Factura';
                } else {
                    return 'Nota de Crédito';
                }
            })
            ->addColumn('activo', function($series){
                if ($series->activo) {
                    return 'Si';
                }else{
                    return 'No';
                }
            })
            ->addColumn('action', function($series){
                return '<a class="btn btn-warning btn-sm" disabled title="Editar Serie"><i class="fa fa-pencil-square-o"></i></a> ' .
                       '<a class="btn btn-danger btn-sm" disabled title="Eliminar Serie"><i class="fa fa-trash-o"></i></a>';
            })->make(true);
        }
    }
}
