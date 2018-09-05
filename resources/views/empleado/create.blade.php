@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('EmpleadoController@store')}}" class="form-horizontal form-label-left" data-toggle="validator">
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
                            <input type="text" id="nro_cedula" name="nro_cedula" class="form-control number" value="{{old('nro_cedula')}}" placeholder="Nro de Cedula de Identidad" autofocus>
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
                        <div class="col-md-5">
                            <input type="text" id="correo_electronico" name="correo_electronico" class="form-control" value="{{old('correo_electronico')}}" placeholder="empleado@email.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="telefono_celular" class="col-md-2 control-label">Tel. Celular*</label>
                        <div class="col-md-4">
                            <input type="text" id="telefono_celular" name="telefono_celular" class="form-control has-feedback-left" value="{{old('telefono_celular')}}" placeholder="(981) 999-999 sin el 0 inicial" data-inputmask="'mask' : '(999) 999-999'">
                            <span class="form-control-feedback left" aria-hidden="true">595</span>
                        </div>
                        <label for="fecha_nacimiento" class="col-md-2 control-label">Fecha Nacimiento*</label>
                        <div class="col-md-3">
                            <input type="text" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control dpfechanacimiento" value="{{old('fecha_nacimiento')}}" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'">
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
@section('otros_scripts')
    <script type="text/javascript">
        $(function() {
          $('.dpfechanacimiento').datepicker({
            format: 'dd/mm/yyyy',
            language: 'es',
            todayBtn: true,
            todayHighlight: true,
            autoclose: true
          });
          $('#fecha_nacimiento').click(function(e){
                    e.stopPropagation();
                    $('.dpfechanacimiento').datepicker('update');
                });  
        });
    </script>
    <script type="text/javascript">
        $("#nro_cedula").on({
            "focus": function (event) {
                $(event.target).select();
            },
            "keyup": function (event) {
                $(event.target).val(function (index, value ) {
                    return value.replace(/\D/g, "")
                                //.replace(/([0-9])([0-9]{2})$/, '$1.$2')
                                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                });
            }
        });
    </script>
@endsection