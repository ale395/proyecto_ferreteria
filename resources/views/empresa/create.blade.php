@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('EmpresaController@store')}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Configuración de Datos de la Empresa</h4>
                </div>
                <div class="panel-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                        </div><br/>
                    @endif
                    <input name="_method" type="hidden" value="POST">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="razon_social" class="col-md-2 control-label">Razon Social *</label>
                        <div class="col-md-5">
                            <input type="text" id="razon_social" name="razon_social" class="form-control" value="{{old('razon_social')}}" autofocus>
                        </div>
                        <label for="ruc" class="col-md-1 control-label">RUC *</label>
                        <div class="col-md-3">
                            <input type="text" id="ruc" name="ruc" class="form-control" value="{{old('ruc')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="direccion" class="col-md-2 control-label">Dirección *</label>
                        <div class="col-md-5">
                            <input type="text" id="direccion" name="direccion" class="form-control" value="{{old('direccion')}}">
                        </div>
                        <label for="telefono" class="col-md-1 control-label">Telefono *</label>
                        <div class="col-md-3">
                            <input type="text" id="telefono" name="telefono" class="form-control" value="{{old('telefono')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sitio_web" class="col-md-2 control-label">Sitio Web</label>
                        <div class="col-md-5">
                            <input type="text" id="sitio_web" name="sitio_web" class="form-control" value="{{old('sitio_web')}}">
                        </div>
                        <label for="correo_electronico" class="col-md-1 control-label">Correo *</label>
                        <div class="col-md-3">
                            <input type="text" id="correo_electronico" name="correo_electronico" class="form-control" value="{{old('correo_electronico')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="eslogan" class="col-md-2 control-label">Eslogan</label>
                        <div class="col-md-5">
                            <input type="text" id="eslogan" name="eslogan" class="form-control" value="{{old('eslogan')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rubro" class="col-md-2 control-label">Rubro *</label>
                        <div class="col-md-5">
                            <input type="text" id="rubro" name="rubro" class="form-control" value="{{old('rubro')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-save">Guardar</button>
                            <a href="{{route('empresa.index')}}" type="button" class="btn btn-default">Cancelar</a>
                        </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection