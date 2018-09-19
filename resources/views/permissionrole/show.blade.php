@extends('home')

@section('content')

    <!-- SECCION QUE LISTA LOS PERMISOS CON LOS QUE YA CUENTA EL ROL -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Listado de Permisos para el rol <b>{{$role->name}}</b> <a href="{{route('gestionpermisos.index')}}" class="btn btn-default pull-right" style="margin-top: -8px;"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver al Listado de Roles</a>
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="showPermission-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permisos as $permiso)
                              <tr>
                                <td>{{$permiso->permiso->slug}}</td>
                                <td>{{$permiso->permiso->name}}</td>
                                <td>{{$permiso->permiso->description}}</td>
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
        var table = $('#showPermission-table').DataTable({
                language: { url: '/datatables/translation/spanish' }
            });
    </script>
@endsection