<div class="modal" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
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
                    <input type="hidden" id="lista_precio_id" name="lista_precio_id" value="{{$list_prec_id}}">
                    
                    <div class="form-group">
                      <label for="articulo_id" class="col-md-3 control-label">Artículo *</label>
                      <div class="col-md-6">
                          <select id="select2-articulos" class="form-control" name="articulo_id" style="width: 100%">
                            @foreach($articulos as $articulo)
                              <option value="{{$articulo->id}}">{{$articulo->articulo}} - {{$articulo->descripcion}}</option>
                            @endforeach
                          </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="fecha_vigencia" class="col-md-3 control-label">Fecha Vigencia *</label>
                      <div class="col-md-6">
                          <input type="text" id="fecha_vigencia" name="fecha_vigencia" class="form-control dpvigencia" data-inputmask="'mask': '99/99/9999'">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="precio" class="col-md-3 control-label">Precio *</label>
                      <div class="col-md-6">
                          <input type="text" id="precio" name="precio" class="form-control">
                          <span class="help-block with-errors"></span>
                      </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-save">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>

            </form>
        </div>
    </div>
</div>