<?php

namespace App\Http\Controllers;

use App\MotivoAnulacion;
use Illuminate\Http\Request;

class MotivoAnulacionController extends Controller
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
        //
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
    public function edit(MotivoAnulacion $motivoAnulacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MotivoAnulacion  $motivoAnulacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MotivoAnulacion $motivoAnulacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MotivoAnulacion  $motivoAnulacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(MotivoAnulacion $motivoAnulacion)
    {
        //
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
}
