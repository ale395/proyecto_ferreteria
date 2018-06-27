@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Roles</h4>
                </div>
                <div class="panel-body">
                    <table id="permisos-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($roles as $role)
                            <tr>
                                <th>{{$role->id}}</th>
                                <th>{{$role->name}}</th>
                                <th>{{$role->description}}</th>
                                <th width="150"><button class="btn btn-warning btn-sm"><i class="fa fa-key" aria-hidden="true"></i> Permisos</button></th>
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
@endsection