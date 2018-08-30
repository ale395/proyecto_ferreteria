<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\FormaPago;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class FormaPagoController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('formaPago.index');
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
            'codigo' => 'required|max:20|unique:formas_pagos,codigo',
            'descripcion' => 'required|max:100',
            'control_valor' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $data = [
            'codigo' => $request['codigo'],
            'descripcion' => $request['descripcion'],
            'control_valor' => $request['control_valor']
        ];

        return FormaPago::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FormaPago  $formaPago
     * @return \Illuminate\Http\Response
     */
    public function show(FormaPago $formaPago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormaPago  $formaPago
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $forma_Pago = FormaPago::findOrFail($id);
        return $forma_Pago;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormaPago  $formaPago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $forma_Pago = FormaPago::findOrFail($id);

        $rules = [
            'codigo' => 'required|max:20|unique:formas_pagos,codigo,'.$forma_Pago->id,
            'descripcion' => 'required|max:100',
            'control_valor' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $forma_Pago->codigo = $request['codigo'];
        $forma_Pago->descripcion = $request['descripcion'];
        $forma_Pago->control_valor = $request['control_valor'];
        
        $forma_Pago->update();

        return $forma_Pago;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormaPago  $formaPago
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return FormaPago::destroy($id);
    }

    public function apiFormasPagos()
    {
        $permiso_editar = Auth::user()->can('formasPagos.edit');
        $permiso_eliminar = Auth::user()->can('formasPagos.destroy');
        $formas_pagos = FormaPago::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($formas_pagos)
                ->addColumn('control_valor', function($formas_pagos){
                    if ($formas_pagos->control_valor) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($formas_pagos){
                    return '<a onclick="editForm('. $formas_pagos->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $formas_pagos->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($formas_pagos)
                ->addColumn('control_valor', function($formas_pagos){
                    if ($formas_pagos->control_valor) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($formas_pagos){
                    return '<a onclick="editForm('. $formas_pagos->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($formas_pagos)
            ->addColumn('control_valor', function($formas_pagos){
                    if ($formas_pagos->control_valor) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
            ->addColumn('action', function($formas_pagos){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $formas_pagos->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($formas_pagos)
            ->addColumn('control_valor', function($formas_pagos){
                    if ($formas_pagos->control_valor) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
            ->addColumn('action', function($formas_pagos){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
