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
                        <label for="num_concepto" class="col-md-3 control-label">Código</label>
                        <div class="col-md-6">
                            <input type="text" id="num_concepto" name="num_concepto" class="form-control" autofocus style="text-transform:uppercase" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="descripcion" class="col-md-3 control-label">Descripción</label>
                      <div class="col-md-6">
                          <input type="text" id="descripcion" name="descripcion" class="form-control" style="text-transform:uppercase" required>
                          <span class="help-block with-errors"></span>
                      </div>
                    </div>

                     <div class="form-group">
                      <label for="signo" class="col-md-3 control-label">Singo</label>
                      <div class="col-md-6">
                          <input type="text" id="signo" name="signo" class="form-control" style="text-transform:uppercase" required>
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