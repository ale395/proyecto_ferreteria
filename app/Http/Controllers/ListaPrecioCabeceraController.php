<?php

namespace App\Http\Controllers;

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
        return view('listaPrecioCabecera.index', compact('lista_precios'));
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
    public function edit(ListaPrecioCabecera $listaPrecioCabecera)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ListaPrecioCabecera  $listaPrecioCabecera
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ListaPrecioCabecera $listaPrecioCabecera)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ListaPrecioCabecera  $listaPrecioCabecera
     * @return \Illuminate\Http\Response
     */
    public function destroy(ListaPrecioCabecera $listaPrecioCabecera)
    {
        //
    }
}
