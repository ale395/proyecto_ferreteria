<?php

namespace App\Http\Controllers;

use App\Pais;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class PaisController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
         return view('pais.index');
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
            'descripcion' => $request['descripcion']
        ];

        return Pais::create($data);
    }

        /**
     * Display the specified resource.
     *
     * @param  \App\Pais  $pais
     * @return \Illuminate\Http\Response
     */
    public function show(Pais $pais)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pais  $pais
     * @return \Illuminate\Http\Response
     */
    public function edit(Pais $pais)
    {
        return $pais;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pais  $pais
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pais $pais)
    {

        $pais->descripcion = $request['descripcion'];
        
        $pais->update();

        return $pais;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pais  $pais
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $pais->delete();
        // return redirect()->route('paises.index');

        return Pais::destroy($id);
    }

      //Función que retorna un JSON con todos los módulos para que los maneje AJAX del lado del servidor
      public function apiPais()
      {
          $permiso_editar = Auth::user()->can('paises.edit');;
          $permiso_eliminar = Auth::user()->can('paises.destroy');;
          $pais = Pais::all();
  
          if ($permiso_editar) {
              if ($permiso_eliminar) {
                  return Datatables::of($pais)
                  ->addColumn('action', function($pais){
                      return '<a onclick="editForm('. $pais->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                             '<a onclick="deleteData('. $pais->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                  })->make(true);
              } else {
                  return Datatables::of($pais)
                  ->addColumn('action', function($pais){
                      return '<a onclick="editForm('. $pais->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                             '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                  })->make(true);
              }
          } elseif ($permiso_eliminar) {
              return Datatables::of($pais)
              ->addColumn('action', function($pais){
                  return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                         '<a onclick="deleteData('. $pais->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
              })->make(true);
          } else {
              return Datatables::of($pais)
              ->addColumn('action', function($pais){
                  return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                         '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
              })->make(true);
          }
      }
}
