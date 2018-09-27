@extends('home')

@section('content')
<div class="row">
    <div class="col-md-12">
        <form id="form-edit-cliente" method="post" action="{{action('ClienteController@update', $cliente->id)}}" class="form-horizontal" data-toggle="validator">
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
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Tipo persona</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          	<div id="tipo_persona" class="btn-group" data-toggle="buttons">
                            	@if($cliente->getTipoPersona() == 'F')
	                            	<label class="btn btn-default active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
	                              	<input id="radioPersonaFisica" type="radio" name="tipo_persona" value="F" checked onchange="validarTipoPersona()" onfocus="personaFisicaAction()" autofocus> &nbsp; Física &nbsp;
	                            	</label>
	                            	<label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
	                              	<input type="radio" name="tipo_persona" value="J" onchange="validarTipoPersona()"> Jurídica
	                            	</label>
                            	@else
                            		<label class="btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
	                              	<input type="radio" name="tipo_persona" value="F" onchange="validarTipoPersona()"> &nbsp; Física &nbsp;
	                            	</label>
	                            	<label class="btn btn-primary active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
	                              	<input id="radioPersonaJuridica" type="radio" name="tipo_persona" value="J" checked onchange="validarTipoPersona()" onfocus="personaJuridicaAction()" autofocus> Jurídica
	                            	</label>
                            	@endif
                          	</div>
                        </div>
                    </div>
                    <br>

                    <div id="divNombre" class="form-group">
                        <label for="nombre" class="col-md-2 control-label">Nombre</label>
                        <div class="col-md-9">
                            <input type="text" id="nombre" name="nombre" class="form-control" value="{{old('nombre',$cliente->getNombre())}}">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div id="divApellido" class="form-group">
                        <label for="apellido" class="col-md-2 control-label">Apellido</label>
                        <div class="col-md-9">
                            <input type="text" id="apellido" name="apellido" class="form-control" value="{{old('apellido',$cliente->getApellido())}}">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div id="divRazonSocial" class="form-group">
                        <label for="razon_social" class="col-md-2 control-label">Razón Social</label>
                        <div class="col-md-9">
                            <input type="text" id="razon_social" name="razon_social" class="form-control" value="{{old('razon_social',$cliente->getRazonSocial())}}">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ruc" class="col-md-2 control-label">RUC</label>
                        <div class="col-md-3">
                            <input type="text" id="ruc" name="ruc" class="form-control" value="{{old('ruc',$cliente->getRuc())}}">
                            <span class="help-block with-errors"></span>
                        </div>
                        <label id="labelNroCedula" for="nro_cedula" class="col-md-3 control-label">Nro Cédula</label>
                        <div id="divNroCedula" class="col-md-3">
                            <input type="text" id="nro_cedula" name="nro_cedula" class="form-control" value="{{old('nro_cedula',$cliente->getNroCedula())}}">
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
                        <label for="telefono_celular" class="col-md-2 control-label">Teléfono Celular</label>
                        <div class="col-md-3">
                            <input type="text" id="telefono_celular" name="telefono_celular" class="form-control" value="{{old('telefono_celular',$cliente->getTelefonoCelular())}}">
                            <span class="help-block with-errors"></span>
                        </div>

                        <label for="correo_electronico" class="col-md-2 control-label">Email</label>
                        <div class="col-md-4">
                            <input type="email" id="correo_electronico" name="correo_electronico" value="{{old('correo_electronico',$cliente->getCorreoElectronico())}}" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telefono_linea_baja" class="col-md-2 control-label">Teléfono Línea Baja</label>
                        <div class="col-md-3">
                            <input type="text" id="telefono_linea_baja" name="telefono_linea_baja" class="form-control" value="{{old('telefono_linea_baja',$cliente->getTelefonoLineaBaja())}}">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo_cliente_id" class="control-label col-md-2">Tipo de Cliente</label>
                        <div class="col-md-4">
                            <select name="tipo_cliente_id" id="select2-tipos" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($tipos_clientes as $id => $tipo_cliente)
                                  <option value="{{ $tipo_cliente->id }}">{{ $tipo_cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label for="zona_id" class="control-label col-md-1">Zona</label>
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
                            @if($cliente->getActivo())
                            	<input id="activo" type="checkbox" class="custom-control-input" name="activo" value="true" checked="checked">
                            @else
                            	<input id="activo" type="checkbox" class="custom-control-input" name="activo" value="true">
                            @endif
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

	function validarTipoPersona(){
		var tipo_persona = $("input[name='tipo_persona']:checked").val();
		
		if (tipo_persona == 'J') {
			personaJuridicaAction();
		} else if(tipo_persona == 'F'){
			personaFisicaAction();
		}
	};

	function personaFisicaAction() {
		$("#radioPersonaFisica").prop('checked', true);
		$('#divRazonSocial').hide();
		$('#divNombre').show();
		$('#divApellido').show();
		$('#labelNroCedula').show();
		$('#divNroCedula').show();
	}

	function personaJuridicaAction() {
		$("#radioPersonaJuridica").prop('checked', true);
		$('#divRazonSocial').show();
		$('#divNombre').hide();
		$('#divApellido').hide();
		$('#labelNroCedula').hide();
		$('#divNroCedula').hide();
	}
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#select2-zonas').select2({
                placeholder : 'Seleccione una de las opciones',
                tags: false,
                width: 'resolve',
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
                language: "es"
            });
        });
</script>
@endsection