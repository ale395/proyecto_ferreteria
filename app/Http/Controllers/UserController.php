<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('user.index', compact('roles'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'role_id' => $request['role_id']
        ];

        return User::create($data);
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        return $user;
    }

    public function destroy($id)
    {
        return User::destroy($id);
    }

    public function update(Request $request, User $user)
    {
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->role_id = $request['role_id'];
        
        $user->update();

        return $user;
    }

    public function apiUsers()
    {
        $permiso_editar = Auth::user()->can('users.edit');;
        $permiso_eliminar = Auth::user()->can('users.destroy');;
        $user = User::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($user)
                ->addColumn('role', function($user){
                    return $user->role->name;
                })
                ->addColumn('action', function($user){
                    return '<a onclick="editForm('. $user->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $user->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($user)
                ->addColumn('action', function($user){
                    return '<a onclick="editForm('. $user->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($user)
            ->addColumn('action', function($user){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $user->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($user)
            ->addColumn('action', function($user){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
