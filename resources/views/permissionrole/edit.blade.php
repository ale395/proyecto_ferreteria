@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Asignación de Permisos para el rol <b>{{$role->name}}</b></h4>
                </div>
                <div class="panel-body">
                    <form action="{{route('gestionpermisos.store')}}" method="post" class="form-horizontal" data-toggle="validator">
                        {{ csrf_field() }} {{ method_field('POST') }}

                        <div class="modal-body">
                            <input type="hidden" id="role_id" name="role_id" value="{{$role->id}}">
                            <div class="form-group">
                              <label for="name" class="col-md-3 control-label">Permiso</label>
                              <div class="col-md-6">
                                  <select class="form-control js-permisos" name="permission_id" style="width: 100%">
                                    <option selected disabled></option>
                                    @foreach($permisos_no_asignados as $permission)
                                      <option value="{{$permission->id}}">{{$permission->name}}</option>
                                    @endforeach
                                  </select>
                              </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-save"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</button>
                            <a href="{{route('gestionpermisos.edit', $role->id)}}" type="button" class="btn btn-default">Cancelar</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCION QUE LISTA LOS PERMISOS CON LOS QUE YA CUENTA EL ROL -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Listado de Permisos para el rol <b>{{$role->name}}</b> <a href="{{route('gestionpermisos.index')}}" class="btn btn-default pull-right" style="margin-top: -8px;"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver al Listado de Roles</a>
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
                                <td width="150">
                                    <form method="post" action="{{route('gestionpermisos.destroy', $permiso->id)}}">
                                        {{csrf_field()}}
                                        {{method_field('DELETE')}}

                                        <button href="{{route('gestionpermisos.destroy', $permiso->id)}}" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</button>
                                    </form>
                                </td>
                              </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
@endsection

@section('otros_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.js-permisos').select2();
        });
    </script>
@endsection