<?php

namespace App\Http\Controllers;

use Validator;
use App\Moneda;
use App\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = Empresa::all();
        if (count($empresas) == 0) {
            return redirect()->action('EmpresaController@create');
        } else{
            $empresa = $empresas[0];
            return redirect()->action('EmpresaController@edit', ['id' => $empresa->id]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $monedas = Moneda::all();
        return view('empresa.create', compact('monedas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresa = new Empresa();

        $rules = [
            'razon_social' => 'required|max:100',
            'ruc' => 'required|max:20',
            'telefono' => 'required|max:20',
            'direccion' => 'required|max:100',
            'rubro' => 'required|max:100',
            'sitio_web' => 'bail|nullable|max:100',
            'eslogan' => 'max:100',
            'correo_electronico' => 'bail|required|max:100|email',
            'codigo_establecimiento' => 'bail|required|numeric|digits:3',
            'moneda_nacional_id' => 'required'
        ];

        $mensajes = [
            'codigo_establecimiento.required' => 'El campo Código de Establecimiento es obligatorio.',
            'codigo_establecimiento.numeric' => 'El valor del Código de Establecimiento debe ser un número.',
            'codigo_establecimiento.digits' => 'El valor del Código de Establecimiento debe tener :digits digitos.',
            'moneda_nacional_id.required' => 'Debe seleccionar la moneda nacional!',
        ];

        Validator::make($request->all(), $rules, $mensajes)->validate();

        $empresa->razon_social = $request['razon_social'];
        $empresa->ruc = $request['ruc'];
        $empresa->sitio_web = $request['sitio_web'];
        $empresa->correo_electronico = $request['correo_electronico'];
        $empresa->direccion = $request['direccion'];
        $empresa->telefono = $request['telefono'];
        $empresa->eslogan = $request['eslogan'];
        $empresa->rubro = $request['rubro'];
        $empresa->codigo_establecimiento = $request['codigo_establecimiento'];
        $empresa->moneda_nacional_id = $request['moneda_nacional_id'];

        $empresa->save();

        $empresas = Empresa::all();
        $id = $empresas[0]->id;

        return redirect('/empresa/'.$id.'/edit')->with('status', 'Datos guardados correctamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);
        $monedas = Moneda::all();
        return view('empresa.edit', compact('empresa', 'monedas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        $rules = [
            'razon_social' => 'required|max:100',
            'ruc' => 'required|max:20',
            'telefono' => 'required|max:20',
            'direccion' => 'required|max:100',
            'rubro' => 'required|max:100',
            'sitio_web' => '|bail|nullable|max:100',
            'eslogan' => 'max:100',
            'correo_electronico' => 'bail|required|max:100|email',
            'codigo_establecimiento' => 'bail|required|numeric|digits:3',
            'moneda_nacional_id' => 'required'
        ];

        $mensajes = [
            'codigo_establecimiento.required' => 'El campo Código de Establecimiento es obligatorio.',
            'codigo_establecimiento.numeric' => 'El valor del Código de Establecimiento debe ser un número.',
            'codigo_establecimiento.digits' => 'El valor del Código de Establecimiento debe tener :digits digitos.',
            'moneda_nacional_id.required' => 'Debe seleccionar la moneda nacional!',
        ];

        Validator::make($request->all(), $rules, $mensajes)->validate();

        $empresa->razon_social = $request['razon_social'];
        $empresa->ruc = $request['ruc'];
        $empresa->sitio_web = $request['sitio_web'];
        $empresa->correo_electronico = $request['correo_electronico'];
        $empresa->direccion = $request['direccion'];
        $empresa->telefono = $request['telefono'];
        $empresa->eslogan = $request['eslogan'];
        $empresa->rubro = $request['rubro'];
        $empresa->codigo_establecimiento = $request['codigo_establecimiento'];
        $empresa->moneda_nacional_id = $request['moneda_nacional_id'];
        
        $empresa->update();

        return redirect('/empresa/'.$empresa->id.'/edit')->with('status', 'Datos guardados correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empresa $empresa)
    {
        //No se implementa el método porque no se debe poder borrar la configuración una vez cargada, solo puede ser modificada
    }
}
