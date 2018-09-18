@extends('home')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Actualización de Precios</h4>
                </div>
                <div class="panel-body">
                    <form action="{{route('gestionpermisos.store')}}" method="post" class="form-horizontal" data-toggle="validator">
                        {{ csrf_field() }} {{ method_field('POST') }}

                        <div class="modal-body">
                            <div class="form-group">
                              <label for="lista_precios" class="col-md-3 control-label">Lista de Precios</label>
                              <div class="col-md-6">
                                  <select name="lista_precios[]" id="select2-listas-precios" class="form-control" style="width: 100%" multiple="multiple">
                                    <option></option>
                                    @foreach($lista_precios as $lista)
                                      <option value="{{$lista->id}}">{{$lista->nombre}}</option>
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
                                      <option value="{{$articulo->id}}">{{$articulo->descripcion}}</option>
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
                                      <option value="{{$familia->id}}">{{$familia->descripcion}}</option>
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
                                      <option value="{{$linea->id}}">{{$linea->descripcion}}</option>
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
                                      <option value="{{$rubro->id}}">{{$rubro->descripcion}}</option>
                                    @endforeach
                                  </select>
                              </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning btn-save"><i class="fa fa-refresh" aria-hidden="true"></i> Actualizar</button>
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
        });
    </script>

@endsection