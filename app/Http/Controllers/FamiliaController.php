<?php

namespace App\Http\Controllers;

use App\Familia;
use Illuminate\Http\Request;
//use App\Http\Requests\CrearFamiliasRequest;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;

class FamiliaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $familias = Familia::all();
        return view('familia.index', compact('familias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('familia.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        /*
        $this->validate($request, [
            'num_familia' => 'required|string|max:10|unique:familias',
            'descripcion' => 'required|string|max:30',
        ]);
        */

        /*aca validamos lo que necesitamos para Familias.
        *el num_familia es requerido, debe ser cadena (alfanumerico) hasta 10 caracteres y no se debe repetir.
        *la descripción tambien es cadena, obligatorio y el tamaño maximo es de 30 caracteres
        */

        
        $validator = Validator::make($request->all(), [
            'num_familia' => 'required|string|max:10|unique:familias',
            'descripcion' => 'required|string|max:30',
        ]);
 
        if ($validator->fails()) {
            return redirect('familias/create')
                        ->withErrors($validator)
                        ->withInput();
        }
        
        Familia::create($request->all())->save();
        return redirect()->route('familias.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function show(Familia $familia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function edit(Familia $familia)
    {
        return view('familia.edit', compact('familia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Familia $familia)
    {
        if (!$familia->isDirty()) {
            $familia->num_familia = $request->num_familia;
            $familia->descripcion = $request->descripcion;
        }

        $familia->save();

        return redirect()->route('familias.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Familia $familia)
    {
        $familia->delete();
        return redirect()->route('familias.index');
    }

    //Función que retorna un JSON con todos los registros para que los maneje AJAX desde el DataTable
    public function apiFamilia()
    {
        $familia = Familia::all();

        return Datatables::of($familia)
            ->addColumn('action', function($familia){
                return '<a onclick="editForm('. $familia->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $familia->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
    }
}
