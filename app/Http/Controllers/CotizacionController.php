<?php

namespace App\Http\Controllers;
use Validator;
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
                   'fecha_cotizacion' => 'required|date_format:"d/m/Y"',
               'moneda_id' => 'required',
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
            'fecha_cotizacion' => date("Y-m-d",strtotime(str_replace('/', '-', $request['fecha_cotizacion']))),
            'moneda_id' => $request['moneda_id'],
            'valor_venta' => $request['valor_venta'],
            'valor_compra' => $request['valor_compra']
        ];

        $cotizacion = Cotizacion::create($data);
        return $cotizacion;
        
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
        $cotizacion = Cotizacion::findOrFail($id);

        $rules = [
            'fecha_cotizacion' => 'required|date_format:"d/m/Y"',
            'moneda_id' => 'required',
         'valor_compra' => 'required',
          'valor_venta' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $cotizacion->fecha_cotizacion = date("Y-m-d",strtotime(str_replace('/', '-',$request['fecha_cotizacion'])));
        $cotizacion->moneda_id = $request['moneda_id'];
        $cotizacion->valor_compra = $request['valor_compra'];
        $cotizacion->valor_venta = $request['valor_venta'];

        
        $cotizacion->update();

        return $cotizacion;
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

    public function apiCotizacionValorVenta($moneda_id){
        if (!empty($moneda_id) && !empty($moneda_id)) {
            
            $cotizacion = Cotizacion::where('moneda_id', $moneda_id)
            ->orderBy('fecha_cotizacion', 'desc')
            ->first();

            /*
            if (!empty($cotizacion)){
                $valor_venta = $cotizacion->getValorVenta();
            } else {
                $valor_venta = 0;
            }
            */
            $valor_venta = $cotizacion->getValorVenta();

            return $valor_venta;
   
        };
    }

    public function apiCotizaciones()
    {
        $permiso_editar = Auth::user()->can('cotizaciones.edit');
        $permiso_eliminar = Auth::user()->can('cotizaciones.destroy');
        $cotizaciones = Cotizacion::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($cotizaciones)
                ->addColumn('moneda', function($cotizaciones){
                    if (empty($cotizaciones->moneda)) {
                         return null;
                     } else {
                        return $cotizaciones->moneda->descripcion;
                    }
                })

                ->addColumn('action', function($cotizaciones){
                    return '<a onclick="editForm('. $cotizaciones->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $cotizaciones->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($cotizaciones)
                ->addColumn('nombre_usuario', function($cotizaciones){
                    if (empty($cotizaciones->moneda)) {
                         return null;
                     } else {
                        return $cotizaciones->moneda->descripcion;
                    }
                })
                ->addColumn('action', function($cotizaciones){
                    return '<a onclick="editForm('. $cotizaciones->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($cotizaciones)
            ->addColumn('nombre_usuario', function($cotizaciones){
                if (empty($cotizaciones->moneda)) {
                    return null;
                } else {
                    return $cotizaciones->moneda->descripicon;
                }
            })

            ->addColumn('action', function($cotizaciones){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $cotizaciones->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($cotizaciones)
            ->addColumn('nombre_usuario', function($cotizaciones){
                if (empty($cotizaciones->moneda)) {
                    return null;
                } else {
                    return $cotizaciones->moneda->descripcion;
                }
            })

            ->addColumn('action', function($cotizaciones){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
