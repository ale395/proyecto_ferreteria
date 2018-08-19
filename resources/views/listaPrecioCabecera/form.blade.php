<div class="modal" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" class="form-horizontal" data-toggle="validator">
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
                      <label for="codigo" class="col-md-3 control-label">Código *</label>
                      <div class="col-md-6">
                          <input type="text" id="codigo" name="codigo" class="form-control">
                          <span class="help-block with-errors"></span>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="nombre" class="col-md-3 control-label">Nombre *</label>
                      <div class="col-md-6">
                          <input type="text" id="nombre" name="nombre" class="form-control">
                          <span class="help-block with-errors"></span>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="moneda_id" class="col-md-3 control-label">Moneda *</label>
                      <div class="col-md-6">
                          <select id="moneda_id" class="form-control" name="moneda_id" style="width: 100%">
                            @foreach($monedas as $moneda)
                              <option value="{{$moneda->id}}">{{$moneda->descripcion}}</option>
                            @endforeach
                          </select>
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