@extends('home')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Actualización de Precios</h4>
                </div>
                <div class="panel-body">
                    <form id="form-actualizar" action="{{route('listaPrecios.actualizarPrecios')}}" method="post" class="form-horizontal" data-toggle="validator">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <div class="modal-body">
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
                                @if (session('status'))
                                    <div class="alert alert-success alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                    {{ session('status') }}
                                    </div>
                                @endif
                        </div>
                        <div class="form-group">
                            <label for="lista_precios" class="col-md-3 control-label">Lista de Precios</label>
                            <div class="col-md-6">
                                <select name="lista_precios[]" id="select2-listas-precios" class="form-control" style="width: 100%" multiple="multiple">
                                <option></option>
                                @foreach($lista_precios as $lista)
                                    <option value="{{$lista->id}}">({{$lista->codigo}}) {{$lista->nombre}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="articulos" class="col-md-3 control-label">Artículos</label>
                            <div class="col-md-6">
                                <select name="articulos[]" id="select2-articulos" class="form-control" style="width: 100%" multiple="multiple">
                                <option></option>
                                @foreach($articulos as $articulo)
                                    <option value="{{$articulo->id}}">({{$articulo->codigo}}) {{$articulo->descripcion}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="familias" class="col-md-3 control-label">Familias</label>
                            <div class="col-md-6">
                                <select name="familias[]" id="select2-familias" class="form-control" style="width: 100%" multiple="multiple">
                                <option></option>
                                @foreach($familias as $familia)
                                    <option value="{{$familia->id}}">({{$familia->num_familia}}) {{$familia->descripcion}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lineas" class="col-md-3 control-label">Líneas</label>
                            <div class="col-md-6">
                                <select name="lineas[]" id="select2-lineas" class="form-control" style="width: 100%" multiple="multiple">
                                <option></option>
                                @foreach($lineas as $linea)
                                    <option value="{{$linea->id}}">({{$linea->num_linea}}) {{$linea->descripcion}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rubros" class="col-md-3 control-label">Rubros</label>
                            <div class="col-md-6">
                                <select name="rubros[]" id="select2-rubros" class="form-control" style="width: 100%" multiple="multiple">
                                <option></option>
                                @foreach($rubros as $rubro)
                                    <option value="{{$rubro->id}}">({{$rubro->num_rubro}}) {{$rubro->descripcion}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="base_calculo" class="col-md-3 control-label">Base de Cálculo*</label>
                            <div class="col-md-6">
                                <select name="base_calculo" id="select2-base-calculo" class="form-control" style="width: 100%">
                                <option value="UC" selected="selected">Último Costo</option>
                                <option value="CP" disabled="disabled">Costo Promedio</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="redondeo" class="col-md-3 control-label">Redondeo*</label>
                            <div class="col-md-6">
                                <select name="redondeo" id="select2-redondeo" class="form-control" style="width: 100%">
                                <option value="1">Decena</option>
                                <option value="2">Centena</option>
                                <option value="3" selected="selected">Unidad de mil</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="porcentaje" class="col-md-3 control-label">Porcentaje de ajuste*</label>
                            <div class="col-md-3">
                            <input type="number" name="porcentaje" class="form-control" placeholder="Ingrese valor en %">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="btn-actualizar" class="btn btn-info btn-save"><i class="fa fa-refresh"></i> Actualizar</button>
                        <a href="{{route('listaPrecios.actualizar')}}" type="button" class="btn btn-default">Cancelar</a>
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('otros_scripts')
	<script type="text/javascript">
        $(document).ready(function(){
            $('#select2-listas-precios').select2({
                placeholder : 'Seleccione una o varias opciones',
                tags: false,
                width: 'resolve',
                language: "es"
            });

            $('#select2-articulos').select2({
                placeholder : 'Seleccione una o varias opciones',
                tags: false,
                width: 'resolve',
                language: "es"
            });

            $('#select2-familias').select2({
                placeholder : 'Seleccione una o varias opciones',
                tags: false,
                width: 'resolve',
                language: "es"
            });

            $('#select2-lineas').select2({
                placeholder : 'Seleccione una o varias opciones',
                tags: false,
                width: 'resolve',
                language: "es"
            });

            $('#select2-rubros').select2({
                placeholder : 'Seleccione una o varias opciones',
                tags: false,
                width: 'resolve',
                language: "es"
            });

            $('#select2-base-calculo').select2({
                placeholder : 'Seleccione una o varias opciones',
                tags: false,
                width: 'resolve',
                language: "es"
            });

            $('#select2-redondeo').select2({
                placeholder : 'Seleccione una o varias opciones',
                tags: false,
                width: 'resolve',
                language: "es"
            });

        });
    </script>
    <script type="text/javascript">
      var askConfirmation = true;
      $("#form-actualizar").submit(function(e){
        if(askConfirmation){
          e.preventDefault();
          $.confirm({
            title: '¿Está seguro de que desea continuar?',
            content: 'Los precios de ventas serán actualizados según los parámetros establecidos',
            type: 'blue',
            buttons: {
              confirm: {
              text: "Actualizar",
              btnClass: 'btn-info',
              action: function(){
                askConfirmation = false;
                $('#form-actualizar').submit();
              }
              },
              cancel: {
                text: "Cancelar",
                btnClass: 'btn-default'
              }
            }
          });
        }
      });
    </script>

@endsection