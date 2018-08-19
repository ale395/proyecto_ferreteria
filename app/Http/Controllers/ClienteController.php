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
        $vendedores = Vendedor::all();
        $lista_precios = ListaPrecioCabecera::all();
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
            'codigo' => 'required|max:20|unique:clientes,codigo',
            'nombre' => 'required|max:100',
            'apellido' => 'max:100',
            'ruc' => 'max:20',
            'nro_documento' => 'unique:clientes,nro_documento',
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

        $data = [
            'codigo' => $request['codigo'],
            'nombre' => $request['nombre'],
            'apellido' => $request['apellido'],
            'ruc' => $request['ruc'],
            'nro_documento' => $request['nro_documento'],
            'telefono' => $request['telefono'],
            'direccion' => $request['direccion'],
            'correo_electronico' => $request['correo_electronico'],
            'zona_id' => $request['zona_id'],
            'tipo_cliente_id' => $request['tipo_cliente_id'],
            'lista_precio_id' => $request['lista_precio_id'],
            'vendedor_id' => $request['vendedor_id'],
            'activo' => $request['activo']
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
        $cliente = Cliente::findOrFail($id);
        return $cliente;
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
        $clientes = Cliente::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($clientes)
                ->addColumn('activo', function($clientes){
                    if ($clientes->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($clientes){
                    return '<a onclick="showForm('. $clientes->id .')" class="btn btn-primary btn-sm" title="Ver Cliente"><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $clientes->id .')" class="btn btn-warning btn-sm" title="Editar Cliente"><i class="fa fa-pencil-square-o"></i></a> ' .
                           '<a onclick="deleteData('. $clientes->id .')" class="btn btn-danger btn-sm" title="Eliminar Cliente"><i class="fa fa-trash-o"></i></a>';
                })->make(true);
            } else {
                return Datatables::of($clientes)
                ->addColumn('activo', function($clientes){
                    if ($clientes->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($clientes){
                    return '<a onclick="editForm('. $clientes->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($clientes)
            ->addColumn('activo', function($clientes){
                    if ($clientes->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
            ->addColumn('action', function($clientes){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $clientes->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($clientes)
            ->addColumn('activo', function($clientes){
                    if ($clientes->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
            ->addColumn('action', function($clientes){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
