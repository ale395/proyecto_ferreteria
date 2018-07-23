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
                      <label for="name" class="col-md-3 control-label">Lista de Precio</label>
                      <div class="col-md-6">
                          <input type="text" id="lista_precio" name="lista_precio" class="form-control" required>
                          <span class="help-block with-errors"></span>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="name" class="col-md-3 control-label">Descripción</label>
                      <div class="col-md-6">
                          <input type="text" id="descripcion" name="descripcion" class="form-control" required>
                          <span class="help-block with-errors"></span>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="name" class="col-md-3 control-label">Moneda</label>
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