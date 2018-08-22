<?php

namespace App\Http\Controllers;

use App\Serie;
use App\Vendedor;
use App\SerieVendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SerieVendedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $serieVendedor = new SerieVendedor;
        $serieVendedor->serie_id = $request->serie_id;
        $serieVendedor->vendedor_id = $request->vendedor_id;
        $serieVendedor->save();
        return redirect('seriesVendedores/'.$request->serie_id.'/edit');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SerieVendedor  $serieVendedor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SerieVendedor  $serieVendedor
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $serie = Serie::findOrFail($id);
        //$vendedores = Vendedor::all();
        $vendedores = DB::table('vendedores')
            ->join('users', 'vendedores.usuario_id', '=', 'users.id')
            ->select('vendedores.id', 'users.name')
            ->whereNotIn('vendedores.id', DB::table('series_vendedores')->where('serie_id', '=', $serie->id)->pluck('vendedor_id'))
            ->get();
        //$vendedores = Vendedor::hydrate($vendedores_no_asignados);
        $series_vendedores = SerieVendedor::where('serie_id', $serie->id)->orderBy('vendedor_id')->get();
        return view('serievendedor.edit', compact('serie', 'vendedores', 'series_vendedores'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SerieVendedor  $serieVendedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SerieVendedor $serieVendedor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SerieVendedor  $serieVendedor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $serieVendedor = SerieVendedor::findOrFail($id);
        $serie_id = $serieVendedor->serie_id;
        $serieVendedor->delete();
        return redirect('seriesVendedores/'.$serie_id.'/edit');
    }
}
