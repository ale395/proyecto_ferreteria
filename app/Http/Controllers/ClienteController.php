<?php

namespace App\Http\Controllers;

use Validator;
use App\Zona;
use App\Cliente;
use App\Vendedor;
use App\ListaPrecioCabecera;
use App\ClasificacionCliente;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zonas = Zona::all();
        $tipos_clientes = ClasificacionCliente::all();
        return view('cliente.index', compact('zonas', 'vendedores', 'lista_precios', 'tipos_clientes'));
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
            'tipo_persona' => 'required',
            'razon_social' => 'required_if:tipo_persona,J|max:100',
            'nombre' => 'required_if:tipo_persona,F|max:100',
            'apellido' => 'required_if:tipo_persona,F|max:100',
            'ruc' => 'required_if:tipo_persona,J|nullable|unique:clientes,ruc|max:20',
            'nro_cedula' => 'required_if:tipo_persona,F|nullable|unique:clientes,nro_cedula'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422);
        }

        $data = [
            'tipo_persona' => $request['tipo_persona'],
            'razon_social' => $request['razon_social'],
            'nombre' => $request['nombre'],
            'apellido' => $request['apellido'],
            'ruc' => $request['ruc'],
            'nro_cedula' => $request['nro_cedula']
        ];

        return Cliente::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return $cliente;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $zonas = Zona::all();
        $tipos_clientes = ClasificacionCliente::all();
        $cliente = Cliente::findOrFail($id);
        return view('cliente.edit', compact('zonas', 'tipos_clientes', 'cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $rules = [
            'codigo' => 'required|max:20|unique:clientes,codigo,'.$cliente->id,
            'nombre' => 'required|max:100',
            'apellido' => 'max:100',
            'ruc' => 'max:20',
            'nro_documento' => 'unique:clientes,nro_documento,'.$cliente->id,
            'telefono' => 'max:20',
            'direccion' => 'max:100',
            'correo_electronico' => 'max:100',
            'zona_id' => 'required',
            'tipo_cliente_id' => 'required',
            'activo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }

        $cliente->codigo = $request['codigo'];
        $cliente->nombre = $request['nombre'];
        $cliente->apellido = $request['apellido'];
        $cliente->ruc = $request['ruc'];
        $cliente->nro_documento = $request['nro_documento'];
        $cliente->telefono = $request['telefono'];
        $cliente->direccion = $request['direccion'];
        $cliente->correo_electronico = $request['correo_electronico'];
        $cliente->zona_id = $request['zona_id'];
        $cliente->tipo_cliente_id = $request['tipo_cliente_id'];
        $cliente->lista_precio_id = $request['lista_precio_id'];
        $cliente->vendedor_id = $request['vendedor_id'];
        $cliente->activo = $request['activo'];
        
        $cliente->update();

        return $cliente;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Cliente::destroy($id);
    }

    public function apiClientes()
    {
        $permiso_editar = Auth::user()->can('clientes.edit');
        $permiso_eliminar = Auth::user()->can('clientes.destroy');
        $permiso_ver = Auth::user()->can('clientes.show');
        $clientes = Cliente::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                if ($permiso_ver) {
                    return Datatables::of($clientes)
                    ->addColumn('tipo_persona', function($clientes){
                        return $clientes->getTipoPersonaIndex();
                    })
                    ->addColumn('nombre', function($clientes){
                        return $clientes->getNombreIndex();
                    })
                    ->addColumn('tipo_documento', function($clientes){
                        return $clientes->getTipoDocumentoIndex();
                    })
                    ->addColumn('nro_documento', function($clientes){
                        return $clientes->getNroDocumentoIndex();
                    })
                    ->addColumn('activo', function($clientes){
                        if ($clientes->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($clientes){
                        return '<a data-toggle="tooltip" data-placement="top" title="Ver mas datos del cliente" onclick="showForm('. $clientes->id .')" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a> ' .'<a data-toggle="tooltip" data-placement="top" onclick="editForm('. $clientes->id .')" class="btn btn-warning btn-sm" title="Editar datos del Cliente"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a data-toggle="tooltip" data-placement="top" onclick="deleteData('. $clientes->id .')" class="btn btn-danger btn-sm" title="Eliminar Cliente"><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                } else{
                    return Datatables::of($clientes)
                    ->addColumn('activo', function($clientes){
                        if ($clientes->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($clientes){
                        return '<a class="btn btn-primary btn-sm" title="Ver Cliente"  disabled><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $clientes->id .')" class="btn btn-warning btn-sm" title="Editar Cliente"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a onclick="deleteData('. $clientes->id .')" class="btn btn-danger btn-sm" title="Eliminar Cliente"><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                }
            } else {
                if ($permiso_ver) {
                    return Datatables::of($clientes)
                    ->addColumn('activo', function($clientes){
                        if ($clientes->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($clientes){
                        return '<a onclick="showForm('. $clientes->id .')" class="btn btn-primary btn-sm" title="Ver Cliente"><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $clientes->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                               '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                    })->make(true);
                } else{
                    return Datatables::of($clientes)
                    ->addColumn('activo', function($clientes){
                        if ($clientes->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($clientes){
                        return '<a class="btn btn-primary btn-sm" title="Ver Cliente" disabled><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $clientes->id .')" class="btn btn-warning btn-sm" title="Editar Cliente"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a class="btn btn-danger btn-sm" title="Eliminar Cliente" disabled><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                }
            }
        } elseif ($permiso_eliminar) {
            if ($permiso_ver) {
                return Datatables::of($clientes)
                ->addColumn('activo', function($clientes){
                        if ($clientes->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                ->addColumn('action', function($clientes){
                    return '<a onclick="showForm('. $clientes->id .')" class="btn btn-primary btn-sm" title="Ver Cliente"><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $clientes->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else{
                return Datatables::of($clientes)
                ->addColumn('activo', function($clientes){
                        if ($clientes->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                ->addColumn('action', function($clientes){
                    return '<a class="btn btn-primary btn-sm" title="Ver Cliente" disabled><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $clientes->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } else {
            if ($permiso_ver) {
                return Datatables::of($clientes)
                ->addColumn('activo', function($clientes){
                        if ($clientes->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                ->addColumn('action', function($clientes){
                    return '<a onclick="showForm('. $clientes->id .')" class="btn btn-primary btn-sm" title="Ver Cliente"><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else{
                return Datatables::of($clientes)
                ->addColumn('activo', function($clientes){
                        if ($clientes->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                ->addColumn('action', function($clientes){
                    return '<a class="btn btn-primary btn-sm" title="Ver Cliente"  disabled><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        }
    }
}
