<?php

namespace App\Http\Controllers;

use Validator;
use App\Empleado;
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
        return view('empleado.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Empleado $empleado)
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Empleado::destroy($id);
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
                        return '<a onclick="showForm('. $empleados->id .')" class="btn btn-primary btn-sm" title="Ver Empleado"><i class="fa fa-eye"></i></a> ' .'<a onclick="editForm('. $empleados->id .')" class="btn btn-warning btn-sm" title="Editar Empleado"><i class="fa fa-pencil-square-o"></i></a> ' .
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
