<?php

namespace App\Http\Controllers;

use App\Moneda;
use App\ListaPrecioCabecera;
use Illuminate\Http\Request;

class ListaPrecioCabeceraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista_precios = ListaPrecioCabecera::all();
        $monedas = Moneda::all();
        return view('listaPrecioCabecera.index', compact('lista_precios', 'monedas'));
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
            'lista_precio' => $request['lista_precio'],
            'descripcion' => $request['descripcion'],
            'moneda_id' => $request['moneda_id']
        ];

        $lista_precio = ListaPrecioCabecera::create($data);

        return $lista_precio;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ListaPrecioCabecera  $listaPrecioCabecera
     * @return \Illuminate\Http\Response
     */
    public function show(ListaPrecioCabecera $listaPrecioCabecera)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ListaPrecioCabecera  $listaPrecioCabecera
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lista_precio = ListaPrecioCabecera::findOrFail($id);
        return $lista_precio;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ListaPrecioCabecera  $listaPrecioCabecera
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $lista_precio = ListaPrecioCabecera::findOrFail($id);
        $lista_precio->lista_precio = $request['lista_precio'];
        $lista_precio->descripcion = $request['descripcion'];
        $lista_precio->moneda_id = $request['moneda_id'];
        
        $lista_precio->update();

        return $lista_precio;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ListaPrecioCabecera  $listaPrecioCabecera
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ListaPrecioCabecera::destroy($id);
    }
}
