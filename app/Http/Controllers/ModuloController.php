<?php

namespace App\Http\Controllers;

use App\Modulo;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class ModuloController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('modulo.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('modulo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Modulo::create($request->all())->save();
        return redirect()->route('modulos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function show(Modulo $modulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function edit(Modulo $modulo)
    {
        return view('modulo.edit', compact('modulo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Modulo $modulo)
    {
        if (!$modulo->isDirty()) {
            $modulo->descripcion = $request->descripcion;
            $modulo->modulo = $request->modulo;
        }

        $modulo->save();

        return redirect()->route('modulos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modulo $modulo)
    {
        $modulo->delete();
        return redirect()->route('modulos.index');
    }

    public function apiModulos(){

        return datatables(Modulo::all())
        ->addColumn('acciones', function(Modulo $modulo){
            return 'Editar/Eliminar';
            /*return '{!! <a href="{{url("/modulos/".$modulo->id."/edit")}}">
                            @can("modulos.edit")
                                <button class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar</button>
                            @else
                                <button class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar</button>
                            @endcan
                        </a>

                        <form style="display: inline" method="POST" action="{{ route("modulos.destroy", $modulo->id) }}">
                            {!! csrf_field() !!}
                            {!! method_field("DELETE") !!}
                            @can("modulos.destroy")
                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</button>
                            @else
                                <button class="btn btn-danger btn-sm" disabled><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</button>
                            @endcan
                        </form> !!}';*/
        })->make(true);
    }
}
