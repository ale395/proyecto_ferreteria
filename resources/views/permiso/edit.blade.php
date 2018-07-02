@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Editar Permisos</h4>
                </div>
                <div class="panel-body">
                    <table id="permisos-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <!--<th>Código</th>-->
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th width="260">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                          
                            <tr>
                                <!--<th>{{$role->id}}</th>-->
                                <th>{{$role->name}}</th>
                                <th>{{$role->description}}</th>
                                <th width="260"><a class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Ver Permisos</a><a class="btn btn-warning btn-sm"><i class="fa fa-key" aria-hidden="true"></i> Editar Permisos</a></th>
                            </tr>
                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
@endsection