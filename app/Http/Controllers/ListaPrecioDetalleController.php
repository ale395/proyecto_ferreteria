<?php

namespace App\Http\Controllers;

use App\ListaPrecioDetalle;
use Illuminate\Http\Request;

class ListaPrecioDetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista_precios_detalle = ListaPrecioDetalle::all();
        return view('listaPrecioDetalle.index', compact('lista_precios_detalle'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ListaPrecioDetalle  $listaPrecioDetalle
     * @return \Illuminate\Http\Response
     */
    public function show(ListaPrecioDetalle $listaPrecioDetalle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ListaPrecioDetalle  $listaPrecioDetalle
     * @return \Illuminate\Http\Response
     */
    public function edit(ListaPrecioDetalle $listaPrecioDetalle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ListaPrecioDetalle  $listaPrecioDetalle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ListaPrecioDetalle $listaPrecioDetalle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ListaPrecioDetalle  $listaPrecioDetalle
     * @return \Illuminate\Http\Response
     */
    public function destroy(ListaPrecioDetalle $listaPrecioDetalle)
    {
        //
    }
}
