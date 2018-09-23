<?php

namespace App\Http\Controllers;

use Image;
use Validator;
use App\Empleado;
use App\TipoEmpleado;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('empleado.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipos_empleados = TipoEmpleado::all();
        return view('empleado.create', compact('tipos_empleados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $empleado = new Empleado();

        if (!empty($request['nro_cedula'])) {
            $request['nro_cedula'] = (integer) str_replace('.', '',$request['nro_cedula']);
        }
        if (!empty($request['telefono_celular'])) {
            $request['telefono_celular'] = (integer)str_replace(" ","",str_replace(")","",str_replace("(","",str_replace("-","",$request['telefono_celular']))));
        }

        $rules = [
            'nro_cedula' => 'required|numeric|unique:empleados,nro_cedula',
            'nombre' => 'required|max:100',
            'apellido' => 'required|max:100',
            'direccion' => 'required|max:100',
            'correo_electronico' => 'required|max:100|email',
            'telefono_celular' => 'required|numeric|digits:9',
            'fecha_nacimiento' => 'required|date_format:d/m/Y',
            'tipos_empleados' => 'required|array|min:1',
        ];

        $mensajes = [
            'nro_cedula.unique' => 'El Nro de Cédula ingresado ya existe!',
            'tipos_empleados.min' => 'Como mínimo se debe asignar :min tipo(s) de empleado(s)!',
        ];

        Validator::make($request->all(), $rules, $mensajes)->validate();

        if($request->hasFile('avatar')){
            
            $avatar = $request->file('avatar');
            $filename = $request['nro_cedula']/*.'-'.time()*/.'.'.$avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(300, 300)->save( public_path('/images/empleados/' . $filename ) );
            $empleado->avatar = $filename;
            
        }

        $empleado->setNroCedula($request['nro_cedula']);
        $empleado->setNombre($request['nombre']);
        $empleado->setApellido($request['apellido']);
        $empleado->setDireccion($request['direccion']);
        $empleado->setCorreoElectronico($request['correo_electronico']);
        $empleado->setTelefonoCelular($request['telefono_celular']);
        $empleado->setFechaNacimiento($request['fecha_nacimiento']);
        
        $empleado->save();

        $empleado->tiposEmpleados()->sync($request->get('tipos_empleados'));

        return redirect('/empleados')->with('status', 'Datos guardados correctamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        $tipos_empleados = TipoEmpleado::all();
        $tipos_empleados_seleccionados = array();

        foreach ($empleado->tiposEmpleados as $key => $tipo_empleado) {
            array_push($tipos_empleados_seleccionados, $tipo_empleado->id);
        }
        
        return view('empleado.edit', compact('empleado', 'tipos_empleados', 'tipos_empleados_seleccionados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        if (!empty($request['nro_cedula'])) {
            $request['nro_cedula'] = (integer) str_replace('.', '',$request['nro_cedula']);
        }
        if (!empty($request['telefono_celular'])) {
            $request['telefono_celular'] = (integer)str_replace(" ","",str_replace(")","",str_replace("(","",str_replace("-","",$request['telefono_celular']))));
        }

        $rules = [
            'nro_cedula' => 'required|numeric|unique:empleados,nro_cedula,'.$empleado->id,
            'nombre' => 'required|max:100',
            'apellido' => 'required|max:100',
            'direccion' => 'required|max:100',
            'correo_electronico' => 'required|max:100|email',
            'telefono_celular' => 'required|numeric|digits:9',
            'fecha_nacimiento' => 'required|date_format:d/m/Y',
            'tipos_empleados' => 'required|array|min:1',
        ];

        $mensajes = [
            'nro_cedula.unique' => 'El Nro de Cédula ingresado ya existe!',
            'tipos_empleados.min' => 'Como mínimo se debe asignar :min tipo(s) de empleado(s)!',
        ];

        Validator::make($request->all(), $rules, $mensajes)->validate();

        if($request->hasFile('avatar')){
            
            $avatar = $request->file('avatar');
            $filename = $request['nro_cedula']/*.'-'.time()*/.'.'.$avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(300, 300)->save( public_path('/images/empleados/' . $filename ) );
            $empleado->avatar = $filename;
            
        }

        $empleado->setNroCedula($request['nro_cedula']);
        $empleado->setNombre($request['nombre']);
        $empleado->setApellido($request['apellido']);
        $empleado->setDireccion($request['direccion']);
        $empleado->setCorreoElectronico($request['correo_electronico']);
        $empleado->setTelefonoCelular($request['telefono_celular']);
        $empleado->setFechaNacimiento($request['fecha_nacimiento']);
        
        $empleado->update();

        $empleado->tiposEmpleados()->sync($request->get('tipos_empleados'));

        return redirect('/empleados')->with('status', 'Datos guardados correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->tiposEmpleados()->detach();
        return Empleado::destroy($id);
    }

    public function deleteSucursal($empleado_id, $sucursal_id){
        $empleado = Empleado::findOrFail($empleado_id);
        $empleado->sucursales()->detach($sucursal_id);
        $empleado->update();
        return;
    }

    public function apiEmpleados()
    {
        $permiso_editar = Auth::user()->can('empleados.edit');
        $permiso_eliminar = Auth::user()->can('empleados.destroy');
        $permiso_ver = Auth::user()->can('empleados.show');
        $empleados = Empleado::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                if ($permiso_ver) {
                    return Datatables::of($empleados)
                    ->addColumn('nro_cedula', function($empleados){
                        return $empleados->getNroCedula();
                    })
                    ->addColumn('telefono_celular', function($empleados){
                        return $empleados->getTelefonoCelular();
                    })
                    ->addColumn('activo', function($empleados){
                        if ($empleados->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($empleados){
                        return '<a onclick="showForm('. $empleados->id .')" class="btn btn-primary btn-sm" title="Ver Empleado"><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $empleados->id .')" class="btn btn-warning btn-sm" title="Editar Empleado"><i class="fa fa-pencil-square-o"></i></a> ' .'<a onclick="editSucursal('. $empleados->id .')" class="btn btn-info btn-sm" title="Editar Sucursales"><i class="fa fa-home"></i></a> ' .
                               '<a onclick="deleteData('. $empleados->id .')" class="btn btn-danger btn-sm" title="Eliminar Empleado"><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                } else{
                    return Datatables::of($empleados)
                    ->addColumn('nro_cedula', function($empleados){
                        return $empleados->getNroCedula();
                    })
                    ->addColumn('telefono_celular', function($empleados){
                        return $empleados->getTelefonoCelular();
                    })
                    ->addColumn('activo', function($empleados){
                        if ($empleados->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($empleados){
                        return '<a class="btn btn-primary btn-sm" title="Ver Empleado"  disabled><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $empleados->id .')" class="btn btn-warning btn-sm" title="Editar Empleado"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a onclick="deleteData('. $empleados->id .')" class="btn btn-danger btn-sm" title="Eliminar Empleado"><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                }
            } else {
                if ($permiso_ver) {
                    return Datatables::of($empleados)
                    ->addColumn('nro_cedula', function($empleados){
                        return $empleados->getNroCedula();
                    })
                    ->addColumn('telefono_celular', function($empleados){
                        return $empleados->getTelefonoCelular();
                    })
                    ->addColumn('activo', function($empleados){
                        if ($empleados->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($empleados){
                        return '<a onclick="showForm('. $empleados->id .')" class="btn btn-primary btn-sm" title="Ver Empleado"><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $empleados->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                               '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                    })->make(true);
                } else{
                    return Datatables::of($empleados)
                    ->addColumn('nro_cedula', function($empleados){
                        return $empleados->getNroCedula();
                    })
                    ->addColumn('telefono_celular', function($empleados){
                        return $empleados->getTelefonoCelular();
                    })
                    ->addColumn('activo', function($empleados){
                        if ($empleados->activo) {
                            return 'Si';
                        }else{
                            return 'No';
                        }
                    })
                    ->addColumn('action', function($empleados){
                        return '<a class="btn btn-primary btn-sm" title="Ver Empleado" disabled><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $empleados->id .')" class="btn btn-warning btn-sm" title="Editar Empleado"><i class="fa fa-pencil-square-o"></i></a> ' .
                               '<a class="btn btn-danger btn-sm" title="Eliminar Empleado" disabled><i class="fa fa-trash-o"></i></a>';
                    })->make(true);
                }
            }
        } elseif ($permiso_eliminar) {
            if ($permiso_ver) {
                return Datatables::of($empleados)
                ->addColumn('nro_cedula', function($empleados){
                    return $empleados->getNroCedula();
                })
                ->addColumn('telefono_celular', function($empleados){
                    return $empleados->getTelefonoCelular();
                })
                ->addColumn('activo', function($empleados){
                    if ($empleados->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($empleados){
                    return '<a onclick="showForm('. $empleados->id .')" class="btn btn-primary btn-sm" title="Ver Empleado"><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $empleados->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else{
                return Datatables::of($empleados)
                ->addColumn('nro_cedula', function($empleados){
                    return $empleados->getNroCedula();
                })
                ->addColumn('telefono_celular', function($empleados){
                    return $empleados->getTelefonoCelular();
                })
                ->addColumn('activo', function($empleados){
                    if ($empleados->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($empleados){
                    return '<a class="btn btn-primary btn-sm" title="Ver Empleado" disabled><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $empleados->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } else {
            if ($permiso_ver) {
                return Datatables::of($empleados)
                ->addColumn('nro_cedula', function($empleados){
                    return $empleados->getNroCedula();
                })
                ->addColumn('telefono_celular', function($empleados){
                    return $empleados->getTelefonoCelular();
                })
                ->addColumn('activo', function($empleados){
                    if ($empleados->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($empleados){
                    return '<a onclick="showForm('. $empleados->id .')" class="btn btn-primary btn-sm" title="Ver Empleado"><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else{
                return Datatables::of($empleados)
                ->addColumn('nro_cedula', function($empleados){
                    return $empleados->getNroCedula();
                })
                ->addColumn('telefono_celular', function($empleados){
                    return $empleados->getTelefonoCelular();
                })
                ->addColumn('activo', function($empleados){
                    if ($empleados->activo) {
                        return 'Si';
                    }else{
                        return 'No';
                    }
                })
                ->addColumn('action', function($empleados){
                    return '<a class="btn btn-primary btn-sm" title="Ver Empleado"  disabled><i class="fa fa-eye"></i></a> ' .'<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        }
    }
}
