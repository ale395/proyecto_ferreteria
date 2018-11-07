@extends('home')

@section('content')
<div class="row">
    <div class="col-md-12">
        <form id="form-edit-cliente" method="post" action="#" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Ver datos del cliente</h4>
                </div>
                <div class="panel-body">
                    <input name="_method" type="hidden" value="GET">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="hidden" id="id" name="id" value="{{$cliente->getId()}}">

                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Tipo persona</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          	@if($cliente->getTipoPersona() == 'F')
                                <input type="text" name="tipo_persona" class="form-control personaFisicaAction" value="{{$cliente->getTipoPersonaIndex()}}" onfocus="personaFisicaAction()" autofocus readonly>
                            @else
                                <input type="text" name="tipo_persona" class="form-control" value="{{$cliente->getTipoPersonaIndex()}}" onfocus="personaJuridicaAction()" autofocus readonly>
                            @endif
                        </div>
                    </div>

                    <div id="divNombre" class="form-group">
                        <label for="nombre" class="col-md-2 control-label">Nombre</label>
                        <div class="col-md-9">
                            <input type="text" id="nombre" name="nombre" class="form-control" value="{{old('nombre',$cliente->getNombre())}}" readonly>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div id="divApellido" class="form-group">
                        <label for="apellido" class="col-md-2 control-label">Apellido</label>
                        <div class="col-md-9">
                            <input type="text" id="apellido" name="apellido" class="form-control" value="{{old('apellido',$cliente->getApellido())}}" readonly>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div id="divRazonSocial" class="form-group">
                        <label for="razon_social" class="col-md-2 control-label">Razón Social</label>
                        <div class="col-md-9">
                            <input type="text" id="razon_social" name="razon_social" class="form-control" value="{{old('razon_social',$cliente->getRazonSocial())}}" readonly>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ruc" class="col-md-2 control-label">RUC</label>
                        <div class="col-md-3">
                            <input type="text" id="ruc" name="ruc" class="form-control" value="{{old('ruc',$cliente->getRuc())}}" readonly>
                            <span class="help-block with-errors"></span>
                        </div>
                        <label id="labelNroCedula" for="nro_cedula" class="col-md-3 control-label">Nro Cédula</label>
                        <div id="divNroCedula" class="col-md-3">
                            <input type="text" id="nro_cedula" name="nro_cedula" class="form-control" value="{{old('nro_cedula',$cliente->getNroCedula())}}" readonly>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="direccion" class="col-md-2 control-label">Dirección</label>
                        <div class="col-md-9">
                            <input type="text" id="direccion" name="direccion" class="form-control" value="{{old('direccion',$cliente->getDireccion())}}" readonly>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telefono_celular" class="col-md-2 control-label">Teléfono Celular</label>
                        <div class="col-md-3">
                            <input type="text" id="telefono_celular" name="telefono_celular" class="form-control" value="{{old('telefono_celular',$cliente->getTelefonoCelular())}}" data-inputmask="'mask' : '(0999) 999-999'" readonly>
                            <span class="help-block with-errors"></span>
                        </div>

                        <label for="correo_electronico" class="col-md-2 control-label">Email</label>
                        <div class="col-md-4">
                            <input type="email" id="correo_electronico" name="correo_electronico" value="{{old('correo_electronico',$cliente->getCorreoElectronico())}}" class="form-control" readonly>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telefono_linea_baja" class="col-md-2 control-label">Teléfono Línea Baja</label>
                        <div class="col-md-3">
                            <input type="text" id="telefono_linea_baja" name="telefono_linea_baja" class="form-control" value="{{old('telefono_linea_baja',$cliente->getTelefonoLineaBaja())}}"  data-inputmask="'mask' : '(099) 999-999'" readonly>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo_cliente_id" class="control-label col-md-2">Tipo de Cliente</label>
                        <div class="col-md-4">
                            <select name="tipo_cliente_id" id="select2-tipos" class="form-control" style="width: 100%" disabled>
                                @if(!empty($tipo_cliente))
                                	<option value="{{ $tipo_cliente->id }}">{{ $tipo_cliente->nombre }}</option>
                                @endif
                            </select>
                        </div>

                        <label for="zona_id" class="control-label col-md-1">Zona</label>
                        <div class="col-md-4">
                            <select name="zona_id" id="select2-zonas" class="select2-input select2" style="width: 100%" disabled>
                            	@if(!empty($zona))
                                	<option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="limite_credito" class="col-md-2 control-label">Límite de Crédito</label>
                        <div class="col-md-3">
                            <input type="text" id="limite_credito" name="limite_credito" class="form-control" value="{{old('limite_credito',$cliente->getLimiteCreditoNumber())}}" readonly>
                        </div>
                        <label for="monto_saldo" class="col-md-2 control-label">Saldo</label>
                        <div class="col-md-3">
                            <input type="text" id="monto_saldo" name="monto_saldo" class="form-control" value="{{old('monto_saldo',$cliente->getMontoSaldoNumber())}}" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Activo *</label>
                        <div class="col-md-1 custom-control custom-checkbox">
                            <input type="hidden" name="activo" value="false">
                            @if($cliente->getActivo())
                            	<input id="activo" type="checkbox" class="custom-control-input" name="activo" value="true" checked="checked" disabled="">
                            @else
                            	<input id="activo" type="checkbox" class="custom-control-input" name="activo" value="true" disabled="">
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <a href="{{route('clientes.index')}}" type="button" class="btn btn-primary">Volver al listado</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('otros_scripts')
<script type="text/javascript">

	function personaFisicaAction() {
		$("#radioPersonaFisica").prop('checked', true);
		$('#divRazonSocial').hide();
		$('#divNombre').show();
		$('#divApellido').show();
		$('#labelNroCedula').show();
		$('#divNroCedula').show();
	};

	function personaJuridicaAction() {
		$("#radioPersonaJuridica").prop('checked', true);
		$('#divRazonSocial').show();
		$('#divNombre').hide();
		$('#divApellido').hide();
		$('#labelNroCedula').hide();
		$('#divNroCedula').hide();
	};
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#select2-zonas').select2({ });

        $('#select2-tipos').select2({ });
    });
</script>
@endsection