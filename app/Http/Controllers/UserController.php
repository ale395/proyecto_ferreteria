<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.index');
    }

    public function apiUsers(){

        return datatables(User::all())
        ->addColumn('acciones', function(){
            return 'Editar/Eliminar';
            /*return '<a href="{{url("/modulos/".$modulo->id."/edit")}}">
                            @can("modulos.edit")
                                <button class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar</button>
                            @else
                                <button class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar</button>
                            @endcan
                        </a>

                        <form style="display: inline" method="POST" action="{{ route("modulos.destroy", $modulo->id) }}">
                            {!! csrf_field() !!}
                            {!! method_field("DELETE") !!}
                            @can("modulos.destroy")
                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</button>
                            @else
                                <button class="btn btn-danger btn-sm" disabled><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</button>
                            @endcan
                        </form>';*/
        })->make(true);
    }
}
