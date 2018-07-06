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
                        <label for="name" class="col-md-3 control-label">Número de Timbrado</label>
                        <div class="col-md-6">
                            <input type="text" id="nro_timbrado" name="nro_timbrado" class="form-control" autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-md-3 control-label">Fecha Vigencia</label>
                        <div class="col-md-6">
                            <input type="text" id="fecha_vigencia" name="fecha_vigencia" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>


                    <div class="form-group">
                      <label for="name" class="col-md-3 control-label">Estado</label>
                      <div class="col-md-6">
                          <select class="form-control" name="estado" id="estado" style="width: 100%">
                              <option value="A">Activo</option>
                              <option value="I">Inactivo</option>
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