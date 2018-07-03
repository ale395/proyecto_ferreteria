<?php

namespace App\Http\Controllers;

use App\Role;
use App\Permission;
use App\PermissionRole;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PermissionRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('permissionrole.index', compact('roles'));
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
        $permissionRole = new PermissionRole;
        $permissionRole->role_id = $request->role_id;
        $permissionRole->permission_id = $request->permission_id;
        $permissionRole->save();
        return redirect('gestionpermisos/'.$request->role_id.'/edit');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PermissionRole  $permissionRole
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        $permisos = PermissionRole::where('role_id', $role->id)->orderBy('permission_id')->get();

        $permisos->map(function ($permisos) {
            $permisos['permiso'] = $permisos->permiso;
            return $permisos;
        });

        return view('permissionrole.show', compact('role', 'permisos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PermissionRole  $permissionRole
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permisos = PermissionRole::where('role_id', $role->id)->orderBy('permission_id')->get();
        
        $permisos_no_asignados = DB::table('permissions')
            ->whereNotIn('id', DB::table('permission_role')->where('role_id', '=', $role->id)->pluck('permission_id'))
            ->get();

        $permisos->map(function ($permisos) {
            $permisos['permiso'] = $permisos->permiso;
            return $permisos;
        });

        return view('permissionrole.edit', compact('role', 'permisos', 'permisos_no_asignados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PermissionRole  $permissionRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PermissionRole $permissionRole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PermissionRole  $permissionRole
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permissionRole = PermissionRole::findOrFail($id);
        $role_id = $permissionRole->role_id;
        $permissionRole->delete();
        return redirect('gestionpermisos/'.$role_id.'/edit');
    }

    //Función que retorna un JSON con todos los roles para que los maneje AJAX del lado del servidor
    public function apiRolePermission()
    {
        $permiso_crear = Auth::user()->can('rolespermission.edit');;
        $permiso_eliminar = Auth::user()->can('rolespermission.destroy');;
        $role = Role::all();

        if ($permiso_crear) {
            if ($permiso_eliminar) {
                return Datatables::of($role)
                ->addColumn('action', function($role){
                    return '<a onclick="editForm('. $role->id .')" class="btn btn-warning btn-sm"><i class="fa fa-key"></i> Agregar Permisos</a> ' .
                           '<a onclick="deleteData('. $role->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar Permisos</a>';
                })->make(true);
            } else {
                return Datatables::of($role)
                ->addColumn('action', function($role){
                    return '<a onclick="editForm('. $role->id .')" class="btn btn-warning btn-sm"><i class="fa fa-key"></i> Agregar Permisos</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar Permisos</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($role)
            ->addColumn('action', function($role){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-key"></i> Agregar Permisos</a> ' .
                       '<a onclick="deleteData('. $role->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar Permisos</a>';
            })->make(true);
        } else {
            return Datatables::of($role)
            ->addColumn('action', function($role){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-key"></i> Agregar Permisos</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar Permisos</a>';
            })->make(true);
        }
    }
}
