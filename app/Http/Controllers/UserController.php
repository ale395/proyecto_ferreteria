<?php

namespace App\Http\Controllers;
use Validator;
use App\User;
use App\Role;
use App\Empleado;
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
        $empleados = Empleado::all();
        return view('user.index', compact('roles','empleados'));
  
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $rules = [
            'empleado_id' => 'required|unique:users,empleado_id'

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors =  json_decode($errors);

            return response()->json(['errors' => $errors], 422); // Status code here
        }
 
        $data = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'role_id' => $request['role_id'],
            'empleado_id' => $request['empleado_id'],
        ];

        $user = User::create($data);


        $user->assignRole($request['role_id']);

        return $user;
    }

    public function show(User $user)
    {
        //
    }

    public function edit($id)
    {

        $user = User::findOrFail($id);
        return $user;
    }

    public function destroy($id)
    {
        return User::destroy($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'empleado_id' => 'required|unique:users,empleado_id,'.$user->id

        ];

            $mensajes = [
                'empleado_id.unique' => 'Este empleado ya tiene asignado un usuario!'
            ];
            $validator = Validator::make($request->all(), $rules, $mensajes);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $errors =  json_decode($errors);
    
                return response()->json(['errors' => $errors], 422); // Status code here
            }
          
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = bcrypt($request['password']);
        $user->empleado_id = $request['empleado_id'];
        if ($user->role_id != $request['role_id']) {
            $user->revokeRole($user->role_id);
            $user->role_id = $request['role_id'];
            $user->assignRole($request['role_id']);
        }
        
        $user->update();

        return $user;
    }

    public function apiUsers()
    {
        $permiso_editar = Auth::user()->can('users.edit');
        $permiso_eliminar = Auth::user()->can('users.destroy');
        $user = User::all();

        if ($permiso_editar) {
            if ($permiso_eliminar) {
                return Datatables::of($user)
                ->addColumn('role', function($user){
                    if (empty($user->role)) {
                         return null;
                     } else {
                        return $user->role->name;
                    }
                })
                ->addColumn('empleado', function($user){
                    if (empty($user->empleado)) {
                         return null;
                     } else {
                        return $user->empleado->nombre;
                    }
                })
                ->addColumn('action', function($user){
                    return '<a onclick="editForm('. $user->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a onclick="deleteData('. $user->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            } else {
                return Datatables::of($user)
                ->addColumn('role', function($user){
                    if (empty($user->role)) {
                         return null;
                     } else {
                        return $user->role->name;
                    }
                })
                ->addColumn('empleado', function($user){
                    if (empty($user->empleado)) {
                         return null;
                     } else {
                        return $user->empleado->nombre;
                    }
                })
                ->addColumn('action', function($user){
                    return '<a onclick="editForm('. $user->id .')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                           '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
            }
        } elseif ($permiso_eliminar) {
            return Datatables::of($user)
            ->addColumn('role', function($user){
                    if (empty($user->role)) {
                         return null;
                     } else {
                        return $user->role->name;
                    }
                })
                ->addColumn('empleado', function($user){
                    if (empty($user->empleado)) {
                         return null;
                     } else {
                        return $user->empleado->nombre;
                    }
                })
            ->addColumn('action', function($user){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a onclick="deleteData('. $user->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        } else {
            return Datatables::of($user)
            ->addColumn('role', function($user){
                    if (empty($user->role)) {
                         return null;
                     } else {
                        return $user->role->name;
                    }
                })
                ->addColumn('empleado', function($user){
                    if (empty($user->empleado)) {
                         return null;
                     } else {
                        return $user->empleado->nombre;
                    }
                })
            ->addColumn('action', function($user){
                return '<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o"></i> Editar</a> ' .
                       '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
