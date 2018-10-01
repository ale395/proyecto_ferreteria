<?php

namespace App\Http\Controllers;

use App\Moneda;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class MonedaController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('moneda.index');
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
             'codigo' => $request['codigo'],
        'descripcion' => $request['descripcion'],
            'simbolo' => $request['simbolo']
        ];

        return Moneda::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Moneda  $moneda
     * @return \Illuminate\Http\Response
     */
    public function show(Moneda $moneda)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Moneda  $moneda
     * @return \Illuminate\Http\Response
     */
    public function edit(Moneda $moneda)
    {
       // dd($moneda);
        return $moneda;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Moneda  $moneda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Moneda $moneda)
    {
             $moneda->codigo = $request['codigo'];
        $moneda->descripcion = $request['descripcion'];
            $moneda->simbolo = $request['simbolo'];
        $moneda->update();

        return $moneda;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Moneda  $moneda
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Moneda::destroy($id);
    }

    public function apiMonedasSelect(Request $request){
        $monedas_array = [];
        
        if($request->has('q')){
            $search = strtolower($request->q);
            $monedas = Moneda::where('descripcion', 'ilike', "%$search%")->get();
        } else {
            $monedas = Moneda::all();
        }

        foreach ($monedas as $moneda) {
            $monedas_array[] = ['id'=> $moneda->getId(), 'text'=> $moneda->getDescripcion()];
        }

        return json_encode($monedas_array);
    }

    //Función que retorna un JSON con todos los módulos para que los maneje AJAX del lado del servidor
    public function apiMonedas()
    {
        $permiso_editar = Auth::user()->can('monedas.edit');;
        $permiso_eliminar = Auth::user()->can('monedas.destroy');;
        $moneda = Moneda::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($moneda)
                ->addColumn('action', function($moneda){
                    return '<a onclick="editForm('. $moneda->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $moneda->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($moneda)
                ->addColumn('action', function($moneda){
                    return '<a onclick="editForm('. $moneda->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($moneda)
            ->addColumn('action', function($moneda){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $moneda->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($moneda)
            ->addColumn('action', function($moneda){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
