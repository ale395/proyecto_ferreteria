<?php

namespace App\Http\Controllers;

use App\Pais;
use App\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{

    public function index()
    {
        $departamentos = Departamento::all();
        return view('departamento.index', compact('departamentos'));
    }

    public function create()
    {
        $paises = Pais::all();
        return view('departamento.create', compact('paises'));
    }

    public function store(Request $request)
    {
        Departamento::create($request->all())->save();
        return redirect()->route('departamentos.index');
    }

    public function show(Departamento $departamento)
    {
        //
    }

    public function edit(Departamento $departamento)
    {
        $paises = Pais::all();
        return view('departamento.edit', compact('departamento', 'paises'));
    }

    public function update(Request $request, Departamento $departamento)
    {
        if (!$departamento->isDirty()) {
            $departamento->descripcion = $request->descripcion;
            $departamento->pais_id = $request->pais_id;
        }

        $departamento->save();

        return redirect()->route('departamentos.index');
    }

    public function destroy(Departamento $departamento)
    {
        $departamento->delete();
        return redirect()->route('departamentos.index');
    }
}
