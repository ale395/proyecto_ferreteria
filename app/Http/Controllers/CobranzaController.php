<?php

namespace App\Http\Controllers;

use App\HabilitacionCaja;
use App\Moneda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CobranzaController extends Controller
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
        $fecha_actual = date('d/m/Y');
        $usuario = Auth::user();
        $habilitacion = HabilitacionCaja::where('user_id', $usuario->getId())
            ->whereNull('fecha_hora_cierre')->first();
        $moneda = Moneda::where('codigo', 'GS')->first();
        $valor_cabmio = 1;

        if (empty('habilitacion')) {
            return redirect()->back();
        }

        return view('cobranza.create', compact('fecha_actual', 'habilitacion', 'moneda', 'valor_cabmio'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
