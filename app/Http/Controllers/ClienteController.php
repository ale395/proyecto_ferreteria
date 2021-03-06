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
        return view('cliente.index');
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
        $cliente = new Cliente;

        if (!empty($request['nro_cedula'])) {
            $request['nro_cedula'] = (integer) str_replace('.', '',$request['nro_cedula']);
        }

        $rules = [
            'tipo_persona' => 'required',
            'razon_social' => 'required_if:tipo_persona,J|max:100',
            'nombre' => 'required_if:tipo_persona,F|max:100',
            'apellido' => 'required_if:tipo_persona,F|max:100',
            'ruc' => 'required_if:tipo_persona,J|nullable|unique:clientes,ruc|max:20',
            'nro_cedula' => 'required_if:tipo_persona,F|nullable|unique:clientes,nro_cedula'
        ];

        $mensajes = [
            'nro_cedula.unique' => 'El Nro de Cédula ingresado ya existe!',
            'ruc.unique' => 'El RUC ingresado ya existe!',
            'razon_social.required_if' => 'El campo Razón Social es requerido cuando el tipo de persona es Jurídica!',
            'ruc.required_if' => 'El campo RUC es requerido cuando el tipo de persona es Jurídica!',
            'nro_cedula.required_if' => 'El campo Nro Cédula es requerido cuando el tipo de persona es Física!',
            'nombre.required_if' => 'El campo Nombre es requerido cuando el tipo de persona es Física!',
            'apellido.required_if' => 'El campo Apellido es requerido cuando el tipo de persona es Física!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422);
        }

        $cliente->setTipoPersona($request['tipo_persona']);
        $cliente->setRazonSocial($request['razon_social']);
        $cliente->setNombre($request['nombre']);
        $cliente->setApellido($request['apellido']);
        $cliente->setRuc($request['ruc']);
        $cliente->setNroCedula($request['nro_cedula']);
        $cliente->save();

        /*$data = [
            'tipo_persona' => $request['tipo_persona'],
            'razon_social' => $request['razon_social'],
            'nombre' => $request['nombre'],
            'apellido' => $request['apellido'],
            'ruc' => $request['ruc'],
            'nro_cedula' => $request['nro_cedula']
        ];*/

        return $cliente;
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
        $zona = $cliente->zona;
        $tipo_cliente = $cliente->tipoCliente;
        return view('cliente.show', compact('cliente', 'tipo_cliente', 'zona'));
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
        $zona = $cliente->zona;
        $tipo_cliente = $cliente->tipoCliente;
        return view('cliente.edit', compact('cliente', 'tipo_cliente', 'zona'));
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
        
        if (!empty($request['nro_cedula'])) {
            $request['nro_cedula'] = (integer) str_replace('.', '',$request['nro_cedula']);
        }

        $rules = [
            'tipo_persona' => 'required',
            'razon_social' => 'required_if:tipo_persona,J|max:100',
            'nombre' => 'required_if:tipo_persona,F|max:100',
            'apellido' => 'required_if:tipo_persona,F|max:100',
            'ruc' => 'required_if:tipo_persona,J|nullable|max:20|unique:clientes,ruc,'.$cliente->id,
            'nro_cedula' => 'required_if:tipo_persona,F|nullable|unique:clientes,nro_cedula,'.$cliente->id,
            'telefono' => 'max:20',
            'direccion' => 'max:100',
            'correo_electronico' => 'max:100',
        ];

        Validator::make($request->all(), $rules)->validate();

        $cliente->setTipoPersona($request['tipo_persona']);
        $cliente->setNombre($request['nombre']);
        $cliente->setApellido($request['apellido']);
        $cliente->setRazonSocial($request['razon_social']);
        $cliente->setRuc($request['ruc']);
        $cliente->setNroCedula($request['nro_cedula']);
        $cliente->setTelefonoCelular($request['telefono_celular']);
        $cliente->setTelefonoLineaBaja($request['telefono_linea_baja']);
        $cliente->setDireccion($request['direccion']);
        $cliente->setCorreoElectronico($request['correo_electronico']);
        $cliente->setZonaId($request['zona_id']);
        $cliente->setTipoClienteId($request['tipo_cliente_id']);
        $cliente->setActivo($request['activo']);
        
        $cliente->update();

        return redirect()->back()->with('status', 'Datos guardados correctamente!');
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

    public function apiClientesVentas(Request $request){
        $clientes_array = [];

        if($request->has('q')){
            $search = strtolower($request->q);
            $clientes = Cliente::where('nombre', 'ilike', "%$search%")
                ->orWhere('apellido', 'ilike', "%$search%")
                ->orWhere('razon_social', 'ilike', "%$search%")
                ->orWhere('ruc', 'ilike', "%$search%")
                //->orWhere('nro_cedula', 'LIKE', "%$search%")
                ->get();
        } else {
            $clientes = Cliente::all();
        }

        foreach ($clientes as $cliente) {
            if ($cliente->getActivo()) {
                $clientes_array[] = ['id'=> $cliente->getId(), 'text'=> $cliente->getNombreSelect()];
            }
        }

        return json_encode($clientes_array);
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
