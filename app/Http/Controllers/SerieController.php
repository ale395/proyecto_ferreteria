<?php

namespace App\Http\Controllers;

use Validator;
use App\Serie;
use App\Sucursal;
use App\Timbrado;
use App\Empleado;
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
        //Recupera solamente los timbrados vigentes a la fecha de hoy
        $timbrados = Timbrado::where('fecha_fin_vigencia', '>=', today())->get();
        //Recupera solamente las sucursales activas
        $sucursales = Sucursal::where('activo', true)->get();
        $empleados = Empleado::where('activo', true)->get();
        $vendedores = $empleados->filter(function ($value, $key) {
            return $value->esVendedor() == true;
        });
        return view('serie.index', compact('timbrados', 'sucursales', 'vendedores'));
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
            'timbrado_id' => 'required',
            'sucursal_id' => 'required|exists:sucursales,id',
            'nro_inicial' => 'bail|required|min:1|integer',
            'nro_final' => 'bail|required|min:1|integer|gt:nro_inicial',
            'vendedor_id' => 'required',
            'activo' => 'required'
        ];

        $mensajes = [
            'sucursal_id.required' => 'El campo Sucursal es obligatorio.',
            'timbrado_id.required' => 'El campo Nro de Timbrado es obligatorio.',
            //'nro_inicial.lt' => 'El Nro Inicial no puede ser mayor al Nro Final de la Serie.',
            'nro_final.gt' => 'El Nro Final no puede ser menor al Nro Inicial de la Serie.',
            'nro_inicial.min' => 'El Nro Inicial no puede ser menor o igual a 0.',
            'nro_final.min' => 'El Nro Final no puede ser menor o igual a 0.',
            'vendedor_id.required' => 'Debe asignar el rango de numeración a un vendedor!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $data = [
            'tipo_comprobante' => $request['tipo_comprobante'],
            'timbrado_id' => $request['timbrado_id'],
            'sucursal_id' => $request['sucursal_id'],
            'nro_inicial' => $request['nro_inicial'],
            'nro_final' => $request['nro_final'],
            'vendedor_id' => $request['vendedor_id'],
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
            'timbrado_id' => 'required',
            'sucursal_id' => 'required|exists:sucursales,id',
            'nro_inicial' => 'bail|required|min:1|integer',
            'nro_final' => 'bail|required|min:1|integer|gt:nro_inicial',
            'vendedor_id' => 'required',
            'activo' => 'required'
        ];

        $mensajes = [
            'sucursal_id.required' => 'El campo Sucursal es obligatorio.',
            'timbrado_id.required' => 'El campo Nro de Timbrado es obligatorio.',
            //'nro_inicial.lt' => 'El Nro Inicial no puede ser mayor al Nro Final de la Serie.',
            'nro_final.gt' => 'El Nro Final no puede ser menor al Nro Inicial de la Serie.',
            'nro_inicial.min' => 'El Nro Inicial no puede ser menor o igual a 0.',
            'nro_final.min' => 'El Nro Final no puede ser menor o igual a 0.',
            'vendedor_id.required' => 'Debe asignar el rango de numeración a un vendedor!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $serie->tipo_comprobante = $request['tipo_comprobante'];
        $serie->timbrado_id = $request['timbrado_id'];
        $serie->sucursal_id = $request['sucursal_id'];
        $serie->nro_inicial = $request['nro_inicial'];
        $serie->nro_final = $request['nro_final'];
        $serie->vendedor_id = $request['vendedor_id'];
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
                ->addColumn('sucursal', function($series){
                    if (empty($series->sucursal)) {
                         return null;
                     } else {
                        return $series->sucursal->nombre;
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
                ->addColumn('sucursal', function($series){
                    if (empty($series->sucursal)) {
                         return null;
                     } else {
                        return $series->sucursal->nombre;
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
            ->addColumn('sucursal', function($series){
                if (empty($series->sucursal)) {
                    return null;
                } else {
                    return $series->sucursal->nombre;
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
            ->addColumn('sucursal', function($series){
                if (empty($series->sucursal)) {
                    return null;
                } else {
                    return $series->sucursal->nombre;
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
