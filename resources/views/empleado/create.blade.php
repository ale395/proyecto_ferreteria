@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('EmpleadoController@store')}}" class="form-horizontal form-label-left" data-toggle="validator" enctype="multipart/form-data">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Nuevo Empleado</h4>
                </div>
                <div class="panel-body">
                <div class="form-group">
                <div class="col-md-9">
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
                        <label for="nro_cedula" class="col-md-3 control-label">Nro CI*</label>
                        <div class="col-md-4">
                            <input type="text" id="nro_cedula" name="nro_cedula" class="form-control number" value="{{old('nro_cedula')}}" placeholder="Nro de Cedula de Identidad" autofocus>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre" class="col-md-3 control-label">Nombre*</label>
                        <div class="col-md-6">
                            <input type="text" id="nombre" name="nombre" class="form-control" value="{{old('nombre')}}" placeholder="Nombre(s) del Empleado">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="apellido" class="col-md-3 control-label">Apellido*</label>
                        <div class="col-md-6">
                            <input type="text" id="apellido" name="apellido" class="form-control" value="{{old('apellido')}}" placeholder="Apellido(s) del Empleado">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="direccion" class="col-md-3 control-label">Dirección*</label>
                        <div class="col-md-6">
                            <input type="text" id="direccion" name="direccion" class="form-control" value="{{old('direccion')}}" placeholder="Domicilio del Empleado">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="correo_electronico" class="col-md-3 control-label">Correo Elect.*</label>
                        <div class="col-md-6">
                            <input type="text" id="correo_electronico" name="correo_electronico" class="form-control" value="{{old('correo_electronico')}}" placeholder="empleado@email.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="telefono_celular" class="col-md-3 control-label">Tel. Celular*</label>
                        <div class="col-md-4">
                            <input type="text" id="telefono_celular" name="telefono_celular" class="form-control" value="{{old('telefono_celular')}}" placeholder="(0999) 999-999" data-inputmask="'mask' : '(0999) 999-999'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fecha_nacimiento" class="col-md-3 control-label">Fecha Nac.*</label>
                        <div class="col-md-4">
                            <input type="text" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control dpfechanacimiento" value="{{old('fecha_nacimiento')}}" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tipo_empleado" class="col-md-3 control-label">Tipo Empleado*</label>
                        <div class="col-md-6">
                            <select name="tipos_empleados[]" id="select2-tipos-empleados" class="form-control" style="width: 100%" multiple="multiple">
                                <option></option>
                                @if (old("tipos_empleados"))
                                	@foreach($tipos_empleados as $id => $tipo_empleado)
	                                  <option value="{{ $tipo_empleado->id }}" {{ (in_array($tipo_empleado->id, old("tipos_empleados")) ? "selected":"") }}>({{ $tipo_empleado->codigo}}) {{ $tipo_empleado->nombre }}</option>
	                                @endforeach
                                @else
                                	@foreach($tipos_empleados as $id => $tipo_empleado)
                                  		<option value="{{ $tipo_empleado->id }}">({{ $tipo_empleado->codigo}}) {{ $tipo_empleado->nombre }}</option>
                                	@endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tipo_empleado" class="col-md-3 control-label">Sucursal por defecto*</label>
                        <div class="col-md-6">
                            <select name="sucursal_default_id" id="select2-sucursales" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($sucursales as $id => $sucursal)
                                  <option value="{{ $sucursal->id }}">({{ $sucursal->codigo}}) {{ $sucursal->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <h5></h5>
                    <img src="{{ URL::to('/') }}/images/default-avatar.jpg" alt="..." class="img-responsive" width="120" height="120">
                    <h5></h5>
                    <input type="file" name="avatar" id="avatar" class="form-control-file" accept=".jpg, .jpeg, .png" style="color: transparent">
                    <span id="label-avatar">default-avatar.jpg</span>
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
                                //.replace(/([0-9])([0-9]{2})$/, '$1.$2') //genera 2 posiciones decimales
                                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                });
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#select2-tipos-empleados').select2({
                placeholder : 'Seleccione una o varias opciones',
                tags: false,
                width: 'resolve',
                language: "es"
            });

            $('#select2-sucursales').select2({
                placeholder : 'Seleccione una opción',
                tags: false,
                width: 'resolve',
                language: "es"
            });
        });
    </script>
    <script type="text/javascript">
        $("#avatar").change(function(){
          var fichero_seleccionado = $(this).val();
          var nombre_fichero_seleccionado = fichero_seleccionado.replace(/.*[\/\\]/, ''); //Eliminamos el path hasta el fichero seleccionado
          if (fichero_seleccionado != nombre_fichero_seleccionado) {
            $("#label-avatar").text(nombre_fichero_seleccionado);
          }
          
        });
    </script>
@endsection