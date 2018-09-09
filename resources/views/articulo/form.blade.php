<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="articulo-form" method="post" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> &times; </span>
                    </button>
                    <h3 class="modal-title"></h3>
                </div>

                <div class="modal-body">
                <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <div id="error-block" class="alert alert-danger">
                        </div>
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
                        <label for="costo" class="col-md-2 control-label">Costo</label>
                        <div class="col-md-4">
                            <input type="number" id="costo" name="costo" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>

                        <label for="porcentaje_ganancia" class="col-md-1 control-label">Porcentaje ganancia</label>
                        <div class="col-md-4">
                            <input type="number" id="porcentaje_ganancia" name="porcentaje_ganancia" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="impuesto_id" class="control-label col-md-2">Impuesto *</label>
                        <div class="col-md-4">
                            <select name="impuesto_id" id="select2-impuestos" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($impuestos as $id => $impuesto)
                                  <option value="{{ $impuesto->id }}">{{ $impuesto->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="familia_id" class="control-label col-md-2">Familia *</label>
                        <div class="col-md-4">
                            <select name="famila_id" id="select2-familias" class="form-control" style="width: 100%">
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
                        <div class="col-md-4">
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
                            <input id="vendible" type="checkbox" class="custom-control-input" name="vendible" value="true">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-2">Existencias *</label>
                        <div class="col-md-1 custom-control custom-checkbox">
                            <input type="hidden" name="control_existencia" value="false">
                            <input id="control_existencia" type="checkbox" class="custom-control-input" name="control_existencia" value="true">
                        </div>
                      </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Activo *</label>
                        <div class="col-md-1 custom-control custom-checkbox">
                            <input type="hidden" name="activo" value="false">
                            <input id="activo" type="checkbox" class="custom-control-input" name="activo" value="true">
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

                <div class="modal-footer">
                    <button id="form-btn-guardar" type="submit" class="btn btn-primary btn-save">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>

            </form>
        </div>
    </div>
</div>