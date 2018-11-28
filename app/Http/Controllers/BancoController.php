<?php

namespace App\Http\Controllers;

use Validator;
use App\Banco;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class BancoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('banco.index');
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
            'codigo' => 'required|max:20|unique:bancos,codigo',
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

        return Banco::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Banco  $banco
     * @return \Illuminate\Http\Response
     */
    public function show(Banco $banco)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banco  $banco
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $banco = Banco::findOrFail($id);
        return $banco;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banco  $banco
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $banco = Banco::findOrFail($id);

        $rules = [
            'codigo' => 'required|max:20|unique:bancos,codigo,'.$banco->id,
            'nombre' => 'required|max:100',
            'activo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $banco->codigo = $request['codigo'];
        $banco->nombre = $request['nombre'];
        $banco->activo = $request['activo'];
        
        $banco->update();

        return $banco;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banco  $banco
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Banco::destroy($id);
    }

    public function apiBancosComprasPagos(Request $request){
        $articulos_array = [];

        if($request->has('q')){
            $search = strtolower($request->q);
            $bancos = Banco::where('descripcion', 'ilike', "%$search%")
                ->get();
        } else {
            $bancos = Banco::all();
        }

        foreach ($bancos as $banco) {
             $bancos_array[] = array('id'=> $banco->getId(), 'text'=> $banco->getNombreSelect());
        }

        return json_encode($bancos_array);
    }

    public function apiBancos()
    {
        $permiso_editar = Auth::user()->can('bancos.edit');
        $permiso_eliminar = Auth::user()->can('bancos.destroy');
        $banco = Banco::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($banco)
                ->addColumn('activo', function($banco){
                    if ($banco->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($banco){
                    return '<a onclick="editForm('. $banco->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $banco->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($banco)
                ->addColumn('activo', function($banco){
                    if ($banco->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($banco){
                    return '<a onclick="editForm('. $banco->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($banco)
            ->addColumn('activo', function($banco){
                    if ($banco->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
            ->addColumn('action', function($banco){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $banco->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($banco)
            ->addColumn('activo', function($banco){
                    if ($banco->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
            ->addColumn('action', function($banco){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
