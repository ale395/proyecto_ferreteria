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
                        <label for="name" class="col-md-3 control-label">Código</label>
                        <div class="col-md-6">
                            <input type="text" id="concepto" name="concepto" class="form-control" autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-md-3 control-label">Nombre</label>
                        <div class="col-md-6">
                            <input type="text" id="nombre_concepto" name="nombre_concepto" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="name" class="col-md-3 control-label">Módulo</label>
                      <div class="col-md-6">
                          <select class="form-control js-modulo" name="modulo_id" id="modulo_id" style="width: 100%">
                            <option></option>
                            @foreach($modulos as $modulo)
                              <option value="{{$modulo->id}}">{{$modulo->descripcion}}</option>
                            @endforeach
                          </select>
                      </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-md-3 control-label">Tipo Concepto</label>
                        <div class="col-md-6">
                            <input type="text" id="tipo_concepto" name="tipo_concepto" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-md-3 control-label">Afecta Stock</label>
                        <div class="col-md-6">
                            <input type="text" id="muev_stock" name="muev_stock" class="form-control" required>
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