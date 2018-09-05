@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('EmpleadoController@store')}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Nuevo Empleado</h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <input name="_method" type="hidden" value="POST">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="nro_cedula" class="col-md-2 control-label">Nro CI*</label>
                        <div class="col-md-3">
                            <input type="text" id="nro_cedula" name="nro_cedula" class="form-control" value="{{old('nro_cedula')}}" placeholder="Nro de Cedula de Identidad" autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nombre" class="col-md-2 control-label">Nombre*</label>
                        <div class="col-md-5">
                            <input type="text" id="nombre" name="nombre" class="form-control" value="{{old('nombre')}}" placeholder="Nombre(s) del Empleado">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="apellido" class="col-md-2 control-label">Apellido*</label>
                        <div class="col-md-5">
                            <input type="text" id="apellido" name="apellido" class="form-control" value="{{old('apellido')}}" placeholder="Apellido(s) del Empleado">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="direccion" class="col-md-2 control-label">Dirección*</label>
                        <div class="col-md-5">
                            <input type="text" id="direccion" name="direccion" class="form-control" value="{{old('direccion')}}" placeholder="Domicilio del Empleado">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="correo_electronico" class="col-md-2 control-label">Correo Electrónico*</label>
                        <div class="col-md-4">
                            <input type="text" id="correo_electronico" name="correo_electronico" class="form-control" value="{{old('correo_electronico')}}" placeholder="Email del Empleado">
                        </div>
                        <label for="fecha_nacimiento" class="col-md-2 control-label">Fecha Nacimiento*</label>
                        <div class="col-md-3">
                            <input type="text" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="{{old('fecha_nacimiento')}}" placeholder="dd/mm/aaaa">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="telefono_celular" class="col-md-2 control-label">Tel. Celular*</label>
                        <div class="col-md-4">
                            <input type="text" id="telefono_celular" name="telefono_celular" class="form-control" value="{{old('telefono_celular')}}" placeholder="Telefono Celular del Empleado">
                        </div>
                        <label for="telefono_linea_baja" class="col-md-2 control-label">Tel. Linea Baja</label>
                        <div class="col-md-3">
                            <input type="text" id="telefono_linea_baja" name="telefono_linea_baja" class="form-control" value="{{old('telefono_linea_baja')}}" placeholder="Telefono fijo del Empleado">
                        </div>
                    </div>
                    <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-save">Guardar</button>
                            <a href="{{route('empleados.index')}}" type="button" class="btn btn-default">Cancelar</a>
                        </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection