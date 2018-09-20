<?php

namespace App\Http\Controllers;

use Validator;
use App\Linea;
use App\Rubro;
use App\Moneda;
use App\Articulo;
use App\Familia;
use App\ListaPrecioDetalle;
use App\ListaPrecioCabecera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $rules = [
            'codigo' => 'required|max:20|unique:lista_precios_cabecera,codigo',
            'nombre' => 'required|max:100',
            'moneda_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $data = [
            'codigo' => $request['codigo'],
            'nombre' => $request['nombre'],
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

        $rules = [
            'codigo' => 'required|max:20|unique:lista_precios_cabecera,codigo,'.$lista_precio->id,
            'nombre' => 'required|max:100',
            'moneda_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        
        $lista_precio->codigo = $request['codigo'];
        $lista_precio->nombre = $request['nombre'];
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

    public function actualizar()
    {
        $lista_precios = ListaPrecioCabecera::all();
        $familias = Familia::all();
        $lineas = Linea::all();
        $rubros = Rubro::all();
        $articulos = Articulo::where('activo', true)->where('vendible', true)->get();

        return view('listaPrecioCabecera.actualizar', compact('lista_precios', 'familias', 'lineas', 'rubros', 'articulos'));
    }

    public function actualizarPrecios(Request $request)
    {
        $rules = [
            'base_calculo' => 'required',
            'redondeo' => 'required',
            'porcentaje' => 'required|numeric'
        ];

        $mensajes = [
            'porcentaje.different' => 'El porcentaje no puede ser 0 (Cero)!',
            'base_calculo.required' => 'Debe seleccionar una base de cálculo!',
            'redondeo' => 'Debe seleccionar un valor de redondeo!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->withErrors($errors);
        }

        $articulos = Articulo::where('activo', true);
        
        if ($request->has('articulos')) {
            $articulos = $articulos->whereIn('id', array($request['articulos']));
        }

        if ($request->has('familias')) {
            $articulos = $articulos->whereIn('familia_id', array($request['familias']));
        }

        if ($request->has('lineas')) {
            $articulos = $articulos->whereIn('linea_id', array($request['lineas']));
        }

        if ($request->has('rubros')) {
            $articulos = $articulos->whereIn('rubro_id', array($request['rubros']));
        }

        if ($request->has('lista_precios')) {
            $listas_precios = ListaPrecioCabecera::whereIn('id', array($request['lista_precios']))->get();
        } else {
            $listas_precios = ListaPrecioCabecera::all();
        }

        $articulos = $articulos->get();

        foreach ($listas_precios as $lista) {
            foreach ($articulos as $articulo) {
                $lista_precio_detalle = new ListaPrecioDetalle();
                $lista_precio_detalle->setListaPrecioId($lista->id);
                $lista_precio_detalle->setArticuloId($articulo->id);
                $lista_precio_detalle->setFechaVigencia(date('d/m/Y'));
                $precio_final = 0;

                //Si es la moneda local queda así tal cual
                //Caso contrario debe buscar la cotización y cambiar de moneda
                if ($request['base_calculo'] == 'UC') {
                    $precio_final = round($articulo->getUltimoCosto() + $articulo->getUltimoCosto()*($request['porcentaje']/100), -$request['redondeo']);
                    $lista_precio_detalle->setPrecio($precio_final);
                } elseif ($request['base_calculo'] == 'CP') {
                    $precio_final = round($articulo->getCostoPromedio() + $articulo->getCostoPromedio()*($request['porcentaje']/100), -$request['redondeo']);
                    $lista_precio_detalle->setPrecio($precio_final);
                }

                $lista_precio_detalle->save();
            }
        }

        return;
    }
}
