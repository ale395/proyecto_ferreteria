@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Asignación de Vendedor</h4>
                </div>
                <div class="panel-body">
                    <form action="{{route('seriesVendedores.store')}}" method="post" class="form-horizontal" data-toggle="validator">
                        {{ csrf_field() }} {{ method_field('POST') }}

                        <div class="modal-body">
                            <input type="hidden" id="serie_id" name="serie_id" value="{{$serie->id}}">
                            <div class="form-group">
                              <label for="vendedor_id" class="col-md-3 control-label">Vendedor *</label>
                              <div class="col-md-6">
                                  <select class="form-control js-vendedores" name="vendedor_id" style="width: 100%">
                                    <option selected disabled></option>
                                    @foreach($vendedores as $vendedor)
                                      <option value="{{$vendedor->id}}">{{$vendedor->name}}</option>
                                    @endforeach
                                  </select>
                              </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-save"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</button>
                            <a href="{{route('seriesVendedores.edit', $serie->id)}}" type="button" class="btn btn-default">Cancelar</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCION QUE LISTA LOS VENDEDORES CON LOS QUE YA CUENTA LA SERIE -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Listado de Vendedores Asignados - Serie <b>{{$serie->serie}}</b> <a href="{{route('series.index')}}" class="btn btn-default pull-right" style="margin-top: -8px;"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver al Listado de Series</a>
                    </h4>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($series_vendedores as $vendedor)
                              <tr>
                                <td>{{$vendedor->vendedor->codigo}}</td>
                                <td>{{$vendedor->vendedor->usuario->name}}</td>
                                <td width="150">
                                    <form method="post" action="{{route('seriesVendedores.destroy', $vendedor->id)}}">
                                        {{csrf_field()}}
                                        {{method_field('DELETE')}}

                                        <button href="{{route('seriesVendedores.destroy', $vendedor->id)}}" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</button>
                                    </form>
                                </td>
                              </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
@endsection

@section('otros_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.js-vendedores').select2({
                placeholder : 'Seleccione una de las opciones',
                tags: false,
                width: 'resolve',
                language: "es"
            });
        });
    </script>
@endsection