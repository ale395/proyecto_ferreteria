<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            'codigo' => 'required|max:20|unique:formaPagos,codigo',
            'nombre' => 'required|max:100',
            'activo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $data = [
            'codigo' => $request['codigo'],
            'nombre' => $request['nombre'],
            'activo' => $request['activo']
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
        $formaPago = FormaPago::findOrFail($id);
        return $formaPago;
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
        $formaPago = FormaPago::findOrFail($id);

        $rules = [
            'codigo' => 'required|max:20|unique:formaPagos,codigo,'.$formaPago->id,
            'nombre' => 'required|max:100',
            'activo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $formaPago->codigo = $request['codigo'];
        $formaPago->nombre = $request['nombre'];
        $formaPago->activo = $request['activo'];
        
        $formaPago->update();

        return $formaPago;
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
        $formaPagos = FormaPago::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($formaPagos)
                ->addColumn('activo', function($formaPagos){
                    if ($formaPagos->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($formaPagos){
                    return '<a onclick="editForm('. $formaPagos->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $formaPagos->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($formaPagos)
                ->addColumn('activo', function($formaPagos){
                    if ($formaPagos->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($formaPagos){
                    return '<a onclick="editForm('. $formaPagos->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($formaPagos)
            ->addColumn('activo', function($formaPagos){
                    if ($formaPagos->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
            ->addColumn('action', function($formaPagos){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $formaPagos->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($formaPagos)
            ->addColumn('activo', function($formaPagos){
                    if ($formaPagos->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
            ->addColumn('action', function($formaPagos){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
