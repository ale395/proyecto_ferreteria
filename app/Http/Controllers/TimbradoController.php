<?php

namespace App\Http\Controllers;

use Validator;
use App\Timbrado;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class TimbradoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('timbrado.index');
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
        //$otros_timbrados = Timbrado::all();

        $rules = [
            'nro_timbrado' => 'required|unique:timbrados,nro_timbrado',
            'fecha_inicio_vigencia' => 'required|date_format:"d/m/Y"',
            'fecha_fin_vigencia' => 'required|date_format:"d/m/Y"|after:fecha_inicio_vigencia|after:today'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(['errors' => $errors], 422); // Status code here
        }

        /*if (!empty($otros_timbrados)) {
            $fecha_inicio = date("Y-m-d",strtotime(str_replace('/', '-', $request['fecha_inicio_vigencia'])));
            $fecha_fin = date("Y-m-d",strtotime(str_replace('/', '-', $request['fecha_fin_vigencia'])));
            $min_fecha_inicio = $otros_timbrados->min('fecha_inicio_vigencia');
            $max_fecha_fin = $otros_timbrados->max('fecha_fin_vigencia');

            if (($fecha_inicio >= $min_fecha_inicio and $fecha_inicio <= $max_fecha_fin) or ($fecha_fin <= $max_fecha_fin and $fecha_fin >= $min_fecha_inicio)) {
                $errors = array('Las fechas establecidas se superponen a otro timbrado ya existente!');
                return response()->json(['errors' => $errors], 422); // Status code here
            }
        }*/

        $data = [
            'nro_timbrado' => $request['nro_timbrado'],
            'fecha_inicio_vigencia' => date("Y-m-d",strtotime(str_replace('/', '-', $request['fecha_inicio_vigencia']))),
            'fecha_fin_vigencia' => date("Y-m-d",strtotime(str_replace('/', '-', $request['fecha_fin_vigencia'])))
        ];

        $timbrado = Timbrado::create($data);

        return $timbrado;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Timbrado  $timbrado
     * @return \Illuminate\Http\Response
     */
    public function show(Timbrado $timbrado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Timbrado  $timbrado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $timbrado = Timbrado::findOrFail($id);
        return $timbrado;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Timbrado  $timbrado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $timbrado = Timbrado::findOrFail($id);
        //$otros_timbrados = Timbrado::where('id', '!=', $id)->get();

        $rules = [
            'nro_timbrado' => 'required|unique:timbrados,nro_timbrado,'.$timbrado->id,
            'fecha_inicio_vigencia' => 'required|date_format:"d/m/Y"',
            'fecha_fin_vigencia' => 'required|date_format:"d/m/Y"|after:fecha_inicio_vigencia'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        /*if (!empty($otros_timbrados)) {
            $fecha_inicio = date("Y-m-d",strtotime(str_replace('/', '-', $request['fecha_inicio_vigencia'])));
            $fecha_fin = date("Y-m-d",strtotime(str_replace('/', '-', $request['fecha_fin_vigencia'])));
            $min_fecha_inicio = $otros_timbrados->min('fecha_inicio_vigencia');
            $max_fecha_fin = $otros_timbrados->max('fecha_fin_vigencia');

            if (($fecha_inicio >= $min_fecha_inicio and $fecha_inicio <= $max_fecha_fin) or ($fecha_fin <= $max_fecha_fin and $fecha_fin >= $min_fecha_inicio)) {
                $errors = array('Las fechas establecidas se superponen a otro timbrado ya existente!');
                return response()->json(['errors' => $errors], 422); // Status code here
            }
        }*/

        $timbrado->nro_timbrado = $request['nro_timbrado'];

        $timbrado->fecha_inicio_vigencia = date("Y-m-d",strtotime(str_replace('/', '-', $request['fecha_inicio_vigencia'])));
        
        $timbrado->fecha_fin_vigencia = date("Y-m-d",strtotime(str_replace('/', '-', $request['fecha_fin_vigencia'])));
        
        $timbrado->update();

        return $timbrado;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Timbrado  $timbrado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Timbrado::destroy($id);
    }

    public function apiTimbrados()
    {
        $permiso_editar = Auth::user()->can('timbrados.edit');;
        $permiso_eliminar = Auth::user()->can('timbrados.destroy');;
        $timbrado = Timbrado::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($timbrado)
                ->addColumn('action', function($timbrado){
                    return '<a onclick="editForm('. $timbrado->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $timbrado->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($timbrado)
                ->addColumn('action', function($timbrado){
                    return '<a onclick="editForm('. $timbrado->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($timbrado)
            ->addColumn('action', function($role){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $timbrado->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($timbrado)
            ->addColumn('action', function($timbrado){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }

}
