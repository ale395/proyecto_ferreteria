@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Permisos para el rol <b>{{$role->name}}</b> <a href="{{route('gestionpermisos.index')}}" class="btn btn-primary pull-right" style="margin-top: -8px;">Volver a Listado de Roles</a>
                    </h4>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permisos as $permiso)
                              <tr>
                                <td>{{$permiso->permiso->name}}</td>
                                <td>{{$permiso->permiso->description}}</td>
                                <td width="150"><a href="{{route('gestionpermisos.destroy', $permiso->id)}}" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</a></td>
                              </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
@endsection