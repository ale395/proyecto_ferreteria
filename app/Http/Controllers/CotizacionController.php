<?php

namespace App\Http\Controllers;
use App\Moneda;
use App\Cotizacion;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class CotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $monedas = Moneda::all();//where('activo', true)->get();
        return view('cotizacion.index', compact('monedas'));
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
                   'fecha' => 'required|date_format:"d/m/Y"',
               'moneda_id' => 'required|unique:cotizaciones,moneda_id',
            'valor_compra' => 'required',
             'valor_venta' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $data = [
            'fecha' => $request['fecha'],
            'moneda_id' => $request['moneda_id'],
            'valor_venta' => $request['valor_venta'],
            'valor_compra' => $request['valor_compra']
        ];

        return Vendedor::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendedor  $vendedor
     * @return \Illuminate\Http\Response
     */
    public function show(Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendedor  $vendedor
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        return $cotizacion;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendedor  $vendedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vendedor = Vendedor::findOrFail($id);

        $rules = [
            'codigo' => 'required|max:20|unique:vendedores,codigo,'.$vendedor->id,
            'usuario_id' => 'required|unique:vendedores,usuario_id,'.$vendedor->id,
            'activo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $vendedor->codigo = $request['codigo'];
        $vendedor->usuario_id = $request['usuario_id'];
        $vendedor->activo = $request['activo'];
        
        $vendedor->update();

        return $vendedor;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendedor  $vendedor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Cotizacion::destroy($id);
    }

    public function apiCotizaciones()
    {
        $permiso_editar = Auth::user()->can('cotizaciones.edit');
        $permiso_eliminar = Auth::user()->can('cotizaciones.destroy');
        $cotizaciones = Cotizacion::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($cotizaciones)
                ->addColumn('nombre_usuario', function($cotizaciones){
                    if (empty($vendedores->usuario)) {
                         return null;
                     } else {
                        return $vendedores->usuario->name;
                    }
                })
                ->addColumn('activo', function($vendedores){
                    if ($vendedores->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($vendedores){
                    return '<a onclick="editForm('. $vendedores->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $vendedores->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($vendedores)
                ->addColumn('nombre_usuario', function($vendedores){
                    if (empty($vendedores->usuario)) {
                         return null;
                     } else {
                        return $vendedores->usuario->name;
                    }
                })
                ->addColumn('activo', function($vendedores){
                    if ($vendedores->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($vendedores){
                    return '<a onclick="editForm('. $vendedores->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($vendedores)
            ->addColumn('nombre_usuario', function($vendedores){
                if (empty($vendedores->usuario)) {
                    return null;
                } else {
                    return $vendedores->usuario->name;
                }
            })
            ->addColumn('activo', function($vendedores){
                if ($vendedores->activo) {
                    return 'Si';
                }else{
                    return 'No';
                }
            })
            ->addColumn('action', function($vendedores){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $vendedores->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($vendedores)
            ->addColumn('nombre_usuario', function($vendedores){
                if (empty($cotizacioness->usuario)) {
                    return null;
                } else {
                    return $cotizaciones->usuario->name;
                }
            })

            ->addColumn('action', function($cotizacioness){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
