@extends('home')

@section('content')

<div id="editDiv" class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('ArticuloController@update', $articulo->id)}}" class="form-horizontal form-label-left" data-toggle="validator" enctype="multipart/form-data">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Editar Articulo</h4>
                </div>
                <div class="panel-body">
                 <div class="form-group">
                 <div class="col-md-12">
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
                    <input type="hidden" id="id" name="id" value="{{$articulo->id}} " >

                    <div class="col-md-12 text-center" >
                    <h5></h5>
                    <img src="{{ URL::to('/') }}/images/articulos/{{$articulo->img_producto}}" alt="..." class="img-responsive" width="120" height="120" style='margin-left: 530px;"'>
                    <h5></h5>
                    <input type="file" name="img_producto" id="img_producto" class="form-control-file" accept=".jpg, .jpeg, .png" style="color: transparent;margin-left: 559px;">
                    <span id="label-img_producto">{{$articulo->img_producto}}</span>
                   </div>
                    <div class="form-group">
                        <label for="codigo" class="col-md-2 control-label">Código *</label>
                        <div class="col-md-3">
                            <input type="text" id="codigo" name="codigo" value="{{old('codigo', $articulo->getCodigo())}}" class="form-control" autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="codigo_barra" class="col-md-2 control-label">Código de barras*</label>
                        <div class="col-md-3">
                            <input type="text" id="codigo_barra" name="codigo_barra"  value="{{old('codigo_barra', $articulo->getCodigoBarra())}}" class="form-control" autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descripcion" class="col-md-2 control-label">Descripcion *</label>
                        <div class="col-md-9">
                            <input type="text" id="descripcion" name="descripcion" value="{{old('descripcion', $articulo->getDescripcion())}}" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ultimo_costo" class="col-md-1 control-label">Ultimo Costo</label>
                        <div class="col-md-2">
                            <input type="number" id="ultimo_costo" name="ultimo_costo"  value="{{old('ultimo_costo', $articulo->getUltimoCosto())}}" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                        <label for="costo_promedio" class="col-md-1 control-label">Costo promedio</label>
                        <div class="col-md-2">
                            <input type="number" id="costo_promedio" name="costo_promedio"  value="{{old('costo_promedio', $articulo->getCostoPromedio())}}"class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                        <label for="porcentaje_ganancia" class="col-md-1 control-label">Porcentaje ganancia</label>
                        <div class="col-sm-1">
                            <input type="number" id="porcentaje_ganancia" name="porcentaje_ganancia"  value="{{old('porcentaje_ganancia', $articulo->getPorcentajeGanancia())}}" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                        <label for="impuesto_id" class="control-label col-md-1">Impuesto *</label>
                        <div class="col-md-2">
                            <select name="impuesto_id" id="select2-impuestos"  class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($impuestos as $id => $impuestos)
                                  <option value="{{ $impuestos->id }}" selected="selected">{{ $impuestos->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>     
                    <div class="form-group">
                        <label for="familia_id" class="control-label col-md-2">Familia *</label>
                        <div class="col-md-4">
                            <select name="familia_id" id="select2-familias" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($familias as $id => $familia)
                                  <option value="{{ $familia->id }}" selected="selected">{{ $familia->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="rubro_id" class="control-label col-md-1">Rubro *</label>
                        <div class="col-md-4">
                            <select name="rubro_id" id="select2-rubros" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($rubros as $id => $rubro)
                                  <option value="{{ $rubro->id }}" selected="selected">{{ $rubro->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="linea_id" class="control-label col-md-2">Linea</label>
                        <div class="col-md-4">
                            <select name="linea_id" id="select2-lineas" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($lineas as $id => $linea)
                                  <option value="{{ $linea->id }}" selected="selected">{{ $linea->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>

                      <label for="unidad_medida_id" class="control-label col-md-1">Unidad de medida</label>
                        <div class="col-md-2">
                            <select name="unidad_medida_id" id="select2-unidades" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($unidadesMedidas as $id => $unidad_medida)
                                  <option value="{{ $unidad_medida->id }}"selected="selected">{{ $unidad_medida->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Vendible *</label>
                        <div class="col-md-1 custom-control custom-checkbox">
                            <input type="hidden" name="vendible" value="false">

                            <input id="vendible" type="checkbox" @if($articulo->vendible)checked @endif class="custom-control-input" name="vendible" value="true">

                        </div>
                        <label class="control-label col-md-2">Existencias *</label>
                        <div class="col-md-1 custom-control custom-checkbox">
                            <input type="hidden" name="control_existencia" value="false">
                            <input id="control_existencia" type="checkbox"  @if($articulo->control_existencia)checked @endif   class="custom-control-input" name="control_existencia" value="true">
                        </div>
                        <label class="control-label col-md-2">Activo *</label>
                        <div class="col-md-1 custom-control custom-checkbox">
                            <input type="hidden" name="activo" value="false">
                            <input id="activo" type="checkbox"  @if($articulo->activo)checked @endif   class="custom-control-input" name="activo" value="true">
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="comentario" class="col-md-2 control-label">Comentario *</label>
                        <div class="col-md-9">
                            <input type="text" id="comentario" name="comentario" value="{{old('comentario', $articulo->getComentario())}}"  class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                 </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-save">Guardar</button>
                <a href="{{route('articulos.index')}}" type="button" class="btn btn-default">Cancelar</a>
            </div>
            
            </div>
     </div>
        </form>
    </div>
</div>
@endsection
@section('otros_scripts')
<script type="text/javascript">
        $("#img_producto").change(function(){
          var fichero_seleccionado = $(this).val();
          var nombre_fichero_seleccionado = fichero_seleccionado.replace(/.*[\/\\]/, ''); //Eliminamos el path hasta el fichero seleccionado
          if (fichero_seleccionado != nombre_fichero_seleccionado) {
            $("#label-img_producto").text(nombre_fichero_seleccionado);
          }
          
        });
    </script>

@endsection