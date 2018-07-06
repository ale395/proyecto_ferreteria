<?php

namespace App\Http\Controllers;

use App\Modulo;
use App\Concepto;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class ConceptoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modulos = Modulo::all();
        return view('concepto.index', compact('modulos'));
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
        $data = [
            'concepto' => $request['concepto'],
            'nombre_concepto' => $request['nombre_concepto'],
            'modulo_id' => $request['modulo_id'],
            'tipo_concepto' => $request['tipo_concepto'],
            'muev_stock' => $request['muev_stock']
        ];

        $concepto = Concepto::create($data);

        return $concepto;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TipoComprobante  $tipoComprobante
     * @return \Illuminate\Http\Response
     */
    public function show(Concepto $concepto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoComprobante  $tipoComprobante
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $concepto = Concepto::findOrFail($id);
        return $concepto;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoComprobante  $tipoComprobante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $concepto = Concepto::findOrFail($id);
        $concepto->concepto = $request['concepto'];
        $concepto->nombre_concepto = $request['nombre_concepto'];
        $concepto->modulo_id = $request['modulo_id'];
        $concepto->tipo_concepto = $request['tipo_concepto'];
        $concepto->muev_stock = $request['muev_stock'];
        
        $concepto->update();

        return $concepto;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TipoComprobante  $tipoComprobante
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Concepto::destroy($id);
    }

    public function apiConceptos()
    {
        $permiso_editar = Auth::user()->can('conceptos.edit');
        $permiso_eliminar = Auth::user()->can('conceptos.destroy');
        $conceptos = Concepto::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($conceptos)
                ->addColumn('muev_stock_name', function($conceptos){
                    if ($conceptos->muev_stock == 'S') {
                         return 'Suma';
                     }
                     elseif ($conceptos->muev_stock == 'R') {
                          return 'Resta';
                      } else {
                        return 'No Aplica';
                    }
                })
                ->addColumn('tipo_concepto', function($conceptos){
                    if ($conceptos->tipo_concepto == 'D') {
                         return 'Débito';
                     }
                     elseif ($conceptos->tipo_concepto == 'C') {
                          return 'Crédito';
                      } else {
                        return 'No Aplica';
                    }
                })
                ->addColumn('modulo', function($conceptos){
                    if (empty($conceptos->modulo)) {
                         return null;
                     } else {
                        return $conceptos->modulo->descripcion;
                    }
                })
                ->addColumn('action', function($conceptos){
                    return '<a onclick="editForm('. $conceptos->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $conceptos->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($conceptos)
                ->addColumn('action', function($conceptos){
                    return '<a onclick="editForm('. $conceptos->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($conceptos)
            ->addColumn('action', function($conceptos){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $conceptos->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($conceptos)
            ->addColumn('action', function($conceptos){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
