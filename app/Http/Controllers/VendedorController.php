<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Vendedor;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class VendedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();//where('activo', true)->get();
        return view('vendedor.index', compact('users'));
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
            'codigo' => 'required|max:20|unique:vendedores,codigo',
            'usuario_id' => 'required|unique:vendedores,usuario_id',
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
            'usuario_id' => $request['usuario_id'],
            'activo' => $request['activo']
        ];

        return Vendedor::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendedor  $vendedor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendedor $vendedor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendedor  $vendedor
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendedor = Vendedor::findOrFail($id);
        return $vendedor;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendedor  $vendedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vendedor = Vendedor::findOrFail($id);

        $rules = [
            'codigo' => 'required|max:20|unique:vendedores,codigo,'.$vendedor->id,
            'usuario_id' => 'required|unique:vendedores,usuario_id,'.$vendedor->id,
            'activo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $vendedor->codigo = $request['codigo'];
        $vendedor->usuario_id = $request['usuario_id'];
        $vendedor->activo = $request['activo'];
        
        $vendedor->update();

        return $vendedor;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendedor  $vendedor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Vendedor::destroy($id);
    }

    public function apiVendedores()
    {
        $permiso_editar = Auth::user()->can('vendedores.edit');
        $permiso_eliminar = Auth::user()->can('vendedores.destroy');
        $vendedores = Vendedor::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($vendedores)
                ->addColumn('nombre_usuario', function($vendedores){
                    if (empty($vendedores->usuario)) {
                         return null;
                     } else {
                        return $vendedores->usuario->name;
                    }
                })
                ->addColumn('activo', function($vendedores){
                    if ($vendedores->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($vendedores){
                    return '<a onclick="editForm('. $vendedores->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $vendedores->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($vendedores)
                ->addColumn('activo', function($vendedores){
                    if ($vendedores->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($vendedores){
                    return '<a onclick="editForm('. $vendedores->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($vendedores)
            ->addColumn('activo', function($vendedores){
                    if ($vendedores->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
            ->addColumn('action', function($vendedores){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $vendedores->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($vendedores)
            ->addColumn('activo', function($vendedores){
                    if ($vendedores->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
            ->addColumn('action', function($vendedores){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
