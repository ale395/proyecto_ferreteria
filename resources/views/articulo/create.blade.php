@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('ArticuloController@store')}}" class="form-horizontal form-label-left" data-toggle="validator" enctype="multipart/form-data">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Nuevo Articulo</h4>
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
                    <input name="_method" type="hidden" value="POST">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="hidden" id="id" name="id">

                     <div class="col-md-12 text-center">
                        <h5></h5>
                        <img id="img_producto_image" src="{{ URL::to('/') }}/images/productos/default-img_producto.jpg" alt="..." class="center-block img-responsive" width="120" height="120">
                        <h5></h5>
                        <input type="file" name="img_producto" id="img_producto" class="form-control-file" accept=".jpg, .jpeg, .png" style="color: transparent;margin-left: 559px;">
                        <span id="label-img_producto">default-img_producto.jpg</span>
                    </div>
                    <div class="form-group">
                        <label for="codigo" class="col-md-2 control-label">Código *</label>
                        <div class="col-md-3">
                            <input type="text" id="codigo" name="codigo" class="form-control" autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="codigo_barra" class="col-md-2 control-label">Código de barras*</label>
                        <div class="col-md-3">
                            <input type="text" id="codigo_barra" name="codigo_barra" class="form-control" autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="descripcion" class="col-md-2 control-label">Descripcion *</label>
                        <div class="col-md-9">
                            <input type="text" id="descripcion" name="descripcion" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ultimo_costo" class="col-md-1 control-label">Ultimo Costo</label>
                        <div class="col-md-2">
                            <input type="number" id="ultimo_costo" name="ultimo_costo" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                        <label for="costo_promedio" class="col-md-1 control-label">Costo promedio</label>
                        <div class="col-md-2">
                            <input type="number" id="costo_promedio" name="costo_promedio" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                        <label for="porcentaje_ganancia" class="col-md-1 control-label">Porcentaje ganancia</label>
                        <div class="col-sm-1">
                            <input type="number" id="porcentaje_ganancia" name="porcentaje_ganancia" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                        <label for="impuesto_id" class="control-label col-md-1">Impuesto *</label>
                        <div class="col-md-2">
                            <select name="impuesto_id" id="select2-impuestos" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($impuestos as $id => $impuesto)
                                  <option value="{{ $impuesto->id }}">{{ $impuesto->descripcion }}</option>
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
                                  <option value="{{ $familia->id }}">{{ $familia->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="rubro_id" class="control-label col-md-1">Rubro *</label>
                        <div class="col-md-4">
                            <select name="rubro_id" id="select2-rubros" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($rubros as $id => $rubro)
                                  <option value="{{ $rubro->id }}">{{ $rubro->descripcion }}</option>
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
                                  <option value="{{ $linea->id }}">{{ $linea->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>

                      <label for="unidad_medida_id" class="control-label col-md-1">Unidad de medida</label>
                        <div class="col-md-2">
                            <select name="unidad_medida_id" id="select2-unidades" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($unidadesMedidas as $id => $unidad_medida)
                                  <option value="{{ $unidad_medida->id }}">{{ $unidad_medida->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Vendible *</label>
                        <div class="col-md-1 custom-control custom-checkbox">
                            <input type="hidden" name="vendible" value="false">
                            <input id="vendible" type="checkbox" class="custom-control-input" checked name="vendible" value="true">
                        </div>
                        <label class="control-label col-md-2">Existencias *</label>
                        <div class="col-md-1 custom-control custom-checkbox">
                            <input type="hidden" name="control_existencia" value="false">
                            <input id="control_existencia" type="checkbox" class="custom-control-input" checked name="control_existencia" value="true">
                        </div>
                        <label class="control-label col-md-2">Activo *</label>
                        <div class="col-md-1 custom-control custom-checkbox">
                            <input type="hidden" name="activo" value="false">
                            <input id="activo" type="checkbox" class="custom-control-input" checked name="activo" value="true">
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="comentario" class="col-md-2 control-label">Comentario *</label>
                        <div class="col-md-9">
                            <input type="text" id="comentario" name="comentario" class="form-control">
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