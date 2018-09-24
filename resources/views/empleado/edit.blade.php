@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('EmpleadoController@update', $empleado->id)}}" class="form-horizontal form-label-left" data-toggle="validator" enctype="multipart/form-data">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Editar Empleado</h4>
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
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="hidden" id="id" name="id" value="{{$empleado->id}}">
                    <div class="form-group">
                        <label for="nro_cedula" class="col-md-3 control-label">Nro CI*</label>
                        <div class="col-md-4">
                            <input type="text" id="nro_cedula" name="nro_cedula" class="form-control number" value="{{old('nro_cedula', $empleado->getNroCedula())}}" placeholder="Nro de Cedula de Identidad" autofocus>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre" class="col-md-3 control-label">Nombre*</label>
                        <div class="col-md-6">
                            <input type="text" id="nombre" name="nombre" class="form-control" value="{{old('nombre', $empleado->getNombre())}}" placeholder="Nombre(s) del Empleado">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="apellido" class="col-md-3 control-label">Apellido*</label>
                        <div class="col-md-6">
                            <input type="text" id="apellido" name="apellido" class="form-control" value="{{old('apellido', $empleado->getApellido())}}" placeholder="Apellido(s) del Empleado">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="direccion" class="col-md-3 control-label">Dirección*</label>
                        <div class="col-md-6">
                            <input type="text" id="direccion" name="direccion" class="form-control" value="{{old('direccion', $empleado->getDireccion())}}" placeholder="Domicilio del Empleado">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="correo_electronico" class="col-md-3 control-label">Correo Elect.*</label>
                        <div class="col-md-6">
                            <input type="text" id="correo_electronico" name="correo_electronico" class="form-control" value="{{old('correo_electronico', $empleado->getCorreoElectronico())}}" placeholder="empleado@email.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="telefono_celular" class="col-md-3 control-label">Tel. Celular*</label>
                        <div class="col-md-4">
                            <input type="text" id="telefono_celular" name="telefono_celular" class="form-control" value="{{old('telefono_celular', $empleado->getTelefonoCelularNumber())}}" placeholder="(0999) 999-999" data-inputmask="'mask' : '(0999) 999-999'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fecha_nacimiento" class="col-md-3 control-label">Fecha Nac.*</label>
                        <div class="col-md-4">
                            <input type="text" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control dpfechanacimiento" value="{{old('fecha_nacimiento', $empleado->getFechaNacimiento())}}" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tipo_empleado" class="col-md-3 control-label">Tipo Empleado*</label>
                        <div class="col-md-6">
                            <select name="tipos_empleados[]" id="select2-tipos-empleados" class="form-control" style="width: 100%" multiple="multiple">
                                <option></option>
                                @foreach($tipos_empleados as $id => $tipo_empleado)
                                  @if(in_array($tipo_empleado->id, $tipos_empleados_seleccionados))
                                    <option value="{{ $tipo_empleado->id }}" selected="selected">({{ $tipo_empleado->codigo}}) {{ $tipo_empleado->nombre }}</option>
                                  @else
                                    <option value="{{ $tipo_empleado->id }}">({{ $tipo_empleado->codigo}}) {{ $tipo_empleado->nombre }}</option>
                                  @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <h4>Sucursales donde puede operar:</h4>
                    <div class="form-group">
                        <div class="col-md-9">
                            <!--<a class="btn btn-primary">Agregar Sucursal</a>-->
                            <a onclick="addForm()" class="btn btn-primary pull-right"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-9">
                        <table id="empleado-sucursal-table" class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th width="30">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <h5></h5>
                    <img src="{{ URL::to('/') }}/images/empleados/{{$empleado->avatar}}" alt="..." class="img-responsive" width="120" height="120">
                    <h5></h5>
                    <input type="file" name="avatar" id="avatar" class="form-control-file" accept=".jpg, .jpeg, .png" style="color: transparent">
                    <span id="label-avatar">{{$empleado->avatar}}</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-save">Guardar</button>
                <a href="{{route('empleados.index')}}" type="button" class="btn btn-default">Cancelar</a>
            </div>
            
            </div>
        </div>
        </form>
        @include('empleado.agregarsucursal')
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
    <script type="text/javascript">
        var empleado = document.getElementById('id').value;
        var table = $('#empleado-sucursal-table').DataTable( {
            "paging":   false,
            "ordering": false,
            "info":     false,
            "searching": false,
            language: { url: '/datatables/translation/spanish' },
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.empleados') }}"+"-sucursales/"+empleado,
            columns: [
                {data: 'codigo', name: 'codigo'},
                {data: 'nombre', name: 'nombre'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        function addForm() {
            save_method = "add";
            $('#error-block').hide();
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('#select2-sucursales').val("").change();
            $('#empleado_id').val(empleado);
            $('.modal-title').text('Agregar Sucursal');
        }

        $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    url = "{{ url('empleados') . '/sucursales' }}";

                    $.ajax({
                        url : url,
                        type : "POST",
                        data : $('#modal-form form').serialize(),
                        success : function($data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                        },
                        error : function(data){
                            $.alert({
                            title: 'Atención!',
                            content: 'Ocurrió un error durante el proceso!',
                            icon: 'fa fa-times-circle-o',
                            type: 'red',
                            });  
                        }
                    });
                    return false;
                }
            });
        });

        function deleteSucursalData(empleado_id, sucursal_id){
        $.confirm({
            title: '¿De verdad lo quieres eliminar?',
            content: 'No podrás volver atras',
            type: 'red',
            buttons: {   
                ok: {
                    text: "Eliminar",
                    btnClass: 'btn-danger',
                    keys: ['enter'],
                    action: function(){
                          var csrf_token = $('meta[name="csrf-token"]').attr('content');
                          
                              $.ajax({
                                  url : "{{ url('empleados') }}" + '/' + empleado_id + '/' + sucursal_id,
                                  type : "POST",
                                  data : {'_method' : 'POST', '_token' : csrf_token},
                                  success : function(data) {
                                      table.ajax.reload();
                                  },
                                  error : function () {
                                          $.alert({
                                              title: 'Atención!',
                                              content: 'Ocurrió un error durante el proceso!',
                                              icon: 'fa fa-times-circle-o',
                                              type: 'red',
                                          });
                                  }
                              })
                    }
                },
                cancel: function(){
                        console.log('Cancel');
                }
            }
          });
        }
    </script>
    <script type="text/javascript">
        $('#select2-sucursales').select2({
            placeholder: 'Seleccione una sucursal',
            language: "es",
            ajax: {
                url: "{{ route('api.empleados.sucursales') }}",
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    </script>
@endsection