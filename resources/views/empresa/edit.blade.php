@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('EmpresaController@update', $empresa->id)}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Configuración de Datos de la Empresa</h4>
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
                        <label for="codigo_establecimiento" class="col-md-2 control-label">Código Establecimiento*</label>
                        <div class="col-md-3">
                            <input type="text" id="codigo_establecimiento" size="3" name="codigo_establecimiento" class="form-control" value="{{old('codigo_establecimiento', $empresa->codigo_establecimiento)}}" placeholder="Código asignado por la SET" autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="razon_social" class="col-md-2 control-label">Razon Social*</label>
                        <div class="col-md-5">
                            <input type="text" id="razon_social" name="razon_social" class="form-control" value="{{old('razon_social', $empresa->razon_social)}}" placeholder="Denominación de la Empresa">
                        </div>
                        <label for="ruc" class="col-md-1 control-label">RUC*</label>
                        <div class="col-md-3">
                            <input type="text" id="ruc" name="ruc" class="form-control" value="{{old('ruc', $empresa->ruc)}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="direccion" class="col-md-2 control-label">Dirección*</label>
                        <div class="col-md-5">
                            <input type="text" id="direccion" name="direccion" class="form-control" value="{{old('direccion',$empresa->direccion)}}" placeholder="Dirección de la Casa Central">
                        </div>
                        <label for="telefono" class="col-md-1 control-label">Telefono*</label>
                        <div class="col-md-3">
                            <input type="text" id="telefono" name="telefono" class="form-control" value="{{old('telefono',$empresa->telefono)}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sitio_web" class="col-md-2 control-label">Sitio Web</label>
                        <div class="col-md-5">
                            <input type="text" id="sitio_web" name="sitio_web" class="form-control" value="{{old('sitio_web',$empresa->sitio_web)}}" placeholder="www.sitioweb.com.py">
                        </div>
                        <label for="correo_electronico" class="col-md-1 control-label">Correo*</label>
                        <div class="col-md-3">
                            <input type="text" id="correo_electronico" name="correo_electronico" class="form-control" value="{{old('correo_electronico',$empresa->correo_electronico)}}" placeholder="correo@email.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="eslogan" class="col-md-2 control-label">Eslogan</label>
                        <div class="col-md-5">
                            <input type="text" id="eslogan" name="eslogan" class="form-control" value="{{old('eslogan',$empresa->eslogan)}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rubro" class="col-md-2 control-label">Rubro*</label>
                        <div class="col-md-5">
                            <input type="text" id="rubro" name="rubro" class="form-control" value="{{old('rubro',$empresa->rubro)}}" placeholder="Rubro o Sector al que se dedica la empresa">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="moneda_nacional_id" class="col-md-2 control-label">Moneda Nacional*</label>
                        <div class="col-md-5">
                            <select name="moneda_nacional_id" id="select2-monedas" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($monedas as $moneda)
                                    @if($empresa->moneda_nacional_id === $moneda->id)
                                        <option value="{{$moneda->id}}" selected="selected">({{$moneda->codigo}}) {{$moneda->descripcion}}</option>
                                    @else
                                        <option value="{{$moneda->id}}">({{$moneda->codigo}}) {{$moneda->descripcion}}</option>
                                    @endif
                                @endforeach
                            </select>
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
@section('otros_scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#select2-monedas').select2({
                placeholder : 'Seleccione una opción',
                tags: false,
                width: 'resolve',
                language: "es"
            });
        });
    </script>
@endsection