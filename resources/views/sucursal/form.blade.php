<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="sucursal-form" method="post" class="form-horizontal" data-toggle="validator">
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
                            <input type="text" id="codigo" name="codigo" class="form-control" autofocus>
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
                      <label for="codigo_punto_expedicion" class="col-md-3 control-label">Punto de Expedición *</label>
                      <div class="col-md-6">
                          <input type="text" id="codigo_punto_expedicion" name="codigo_punto_expedicion" class="form-control">
                          <span class="help-block with-errors"></span>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="direccion" class="col-md-3 control-label">Direccion</label>
                      <div class="col-md-6">
                          <input type="text" id="direccion" name="direccion" class="form-control">
                          <span class="help-block with-errors"></span>
                      </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Activo *</label>
                          <div class="col-md-6 custom-control custom-checkbox">
                            <input type="hidden" name="activo" value="false">
                            <input id="activo" type="checkbox" class="custom-control-input" name="activo" value="true">
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