@extends('home')

@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('ClienteController@update', $cliente->id)}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Editar datos del cliente</h4>
                </div>
                <div class="panel-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div><br/>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                            {{ session('status') }}
                        </div>
                    @endif
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="nombre" class="col-md-2 control-label">Nombre *</label>
                        <div class="col-md-9">
                            <input type="text" id="nombre" name="nombre" class="form-control" value="{{old('nombre',$cliente->nombre)}}">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="apellido" class="col-md-2 control-label">Apellido</label>
                        <div class="col-md-9">
                            <input type="text" id="apellido" name="apellido" class="form-control" value="{{old('apellido',$cliente->apellido)}}">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nro_cedula" class="col-md-2 control-label">Nro Cédula</label>
                        <div class="col-md-4">
                            <input type="number" id="nro_cedula" name="nro_cedula" class="form-control" value="{{old('nro_cedula',$cliente->nro_cedula)}}">
                            <span class="help-block with-errors"></span>
                        </div>

                        <label for="ruc" class="col-md-1 control-label">RUC</label>
                        <div class="col-md-4">
                            <input type="text" id="ruc" name="ruc" class="form-control" value="{{old('ruc',$cliente->ruc)}}">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="direccion" class="col-md-2 control-label">Dirección</label>
                        <div class="col-md-9">
                            <input type="text" id="direccion" name="direccion" class="form-control" value="{{old('direccion',$cliente->getDireccion())}}">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telefono_celular" class="col-md-2 control-label">Nro Teléfono</label>
                        <div class="col-md-4">
                            <input type="text" id="telefono_celular" name="telefono_celular" class="form-control" value="{{old('telefono_celular',$cliente->getTelefonoCelular())}}">
                            <span class="help-block with-errors"></span>
                        </div>

                        <label for="correo_electronico" class="col-md-1 control-label">Email</label>
                        <div class="col-md-4">
                            <input type="email" id="correo_electronico" name="correo_electronico" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo_cliente_id" class="control-label col-md-2">Tipo de Cliente *</label>
                        <div class="col-md-4">
                            <select name="tipo_cliente_id" id="select2-tipos" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($tipos_clientes as $id => $tipo_cliente)
                                  <option value="{{ $tipo_cliente->id }}">{{ $tipo_cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label for="zona_id" class="control-label col-md-1">Zona *</label>
                        <div class="col-md-4">
                            <select name="zona_id" id="select2-zonas" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($zonas as $id => $zona)
                                  <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Activo *</label>
                        <div class="col-md-1 custom-control custom-checkbox">
                            <input type="hidden" name="activo" value="false">
                            <input id="activo" type="checkbox" class="custom-control-input" name="activo" value="true">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save">Guardar</button>
                        <a href="{{route('clientes.index')}}" type="button" class="btn btn-default">Cancelar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('otros_scripts')
  <script type="text/javascript">
    $(document).ready(function(){
        $('#select2-zonas').select2({
                placeholder : 'Seleccione una de las opciones',
                tags: false,
                width: 'resolve',
                dropdownParent: $('#modal-form'),
                language: "es"
            });
        });
  </script>

  <script type="text/javascript">
    $(document).ready(function(){
            $('#select2-tipos').select2({
                placeholder : 'Seleccione una de las opciones',
                tags: false,
                width: 'resolve',
                dropdownParent: $('#modal-form'),
                language: "es"
            });
        });
  </script>
@endsection