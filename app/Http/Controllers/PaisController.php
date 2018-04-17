<?php

namespace App\Http\Controllers;

use App\Pais;
use Illuminate\Http\Request;

class PaisController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $paises = Pais::paginate(10);
        return view('pais.index', compact('paises'));
    }

    public function create()
    {
        return view('pais.create');
    }

    public function store(Request $request)
    {
        Pais::create($request->all())->save();
        return redirect()->route('paises.index');
    }

    public function show(Pais $pais)
    {
        //
    }


    public function edit(Pais $pais)
    {
        return view('pais.edit', compact('pais'));
    }

    public function update(Request $request, Pais $pais)
    {
        if (!$pais->isDirty()) {
            $pais->descripcion = $request->descripcion;
              $pais->pais = $request->pais;
        }

        $pais->save();

        return redirect()->route('paises.index');
    }

    public function destroy(Pais $pais)
    {
        $pais->delete();
        return redirect()->route('paises.index');
    }
}
