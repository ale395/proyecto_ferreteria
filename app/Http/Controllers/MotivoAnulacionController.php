<?php

namespace App\Http\Controllers;

use Validator;
use App\MotivoAnulacion;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class MotivoAnulacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('motivoAnulacion.index');
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
            'nombre' => 'required|max:100',
        ];

        $mensajes = [
            'nombre.required' => 'El campo Nombre es obligatorio!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $data = [
            'nombre' => $request['nombre'],
        ];

        return MotivoAnulacion::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MotivoAnulacion  $motivoAnulacion
     * @return \Illuminate\Http\Response
     */
    public function show(MotivoAnulacion $motivoAnulacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MotivoAnulacion  $motivoAnulacion
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $motivo = MotivoAnulacion::findOrFail($id);
        return $motivo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MotivoAnulacion  $motivoAnulacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $motivo = MotivoAnulacion::findOrFail($id);

        $rules = [
            'nombre' => 'required|max:100',
        ];

        $mensajes = [
            'nombre.required' => 'El campo Nombre es obligatorio!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);
            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $motivo->setNombre($request['nombre']);
        $motivo->update();

        return $motivo;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MotivoAnulacion  $motivoAnulacion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return MotivoAnulacion::destroy($id);
    }

    public function apiMotivosAnulaciones(Request $request){
        $motivos_array = [];

        if($request->has('q')){
            $search = strtolower($request->q);
            $motivos = MotivoAnulacion::where('nombre', 'ilike', "%$search%")->get();
        } else {
            $motivos = MotivoAnulacion::all();
        }

        foreach ($motivos as $motivo) {
            $motivos_array[] = ['id'=> $motivo->getId(), 'text'=> $motivo->getNombre()];
        }

        return json_encode($motivos_array);
    }

    public function apiMotivosAnulacionesIndex(){
        $permiso_editar = Auth::user()->can('motivoanulacion.edit');
        $permiso_borrar = Auth::user()->can('motivoanulacion.destroy');
        $motivos = MotivoAnulacion::all();

        if ($permiso_editar) {
            if ($permiso_borrar) {
                return Datatables::of($motivos)
                ->addColumn('action', function($motivos){
                    return '<a onclick="editForm('. $motivos->id .')" class="btn btn-warning btn-sm" title="Editar Motivo"><i class="fa fa-pencil-square-o"></i></a>'.'<a onclick="deleteData('. $motivos->id .')" class="btn btn-danger btn-sm" title="Eliminar Motivo"><i class="fa fa-trash-o"></i></a>';
                })->make(true);
            } else {
                return Datatables::of($motivos)
                ->addColumn('action', function($motivos){
                    return '<a onclick="editForm('. $motivos->id .')" class="btn btn-warning btn-sm" title="Editar Motivo"><i class="fa fa-pencil-square-o"></i></a>'.'<a class="btn btn-danger btn-sm" title="Eliminar Motivo" disabled><i class="fa fa-trash-o"></i></a>';
                })->make(true);
            }
        } else {
            if ($permiso_borrar) {
                return Datatables::of($motivos)
                ->addColumn('action', function($motivos){
                    return '<a class="btn btn-warning btn-sm" title="Editar Motivo" disabled><i class="fa fa-pencil-square-o"></i></a>'.'<a onclick="deleteData('. $motivos->id .')" class="btn btn-danger btn-sm" title="Eliminar Motivo"><i class="fa fa-trash-o"></i></a>';
                })->make(true);
            } else {
                return Datatables::of($motivos)
                ->addColumn('action', function($motivos){
                    return '<a class="btn btn-warning btn-sm" title="Editar Motivo" disabled><i class="fa fa-pencil-square-o"></i></a>'.'<a class="btn btn-danger btn-sm" title="Eliminar Motivo" disabled><i class="fa fa-trash-o"></i></a>';
                })->make(true);
            }
        }
    }
}
