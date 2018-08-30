<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="serie-form" method="post" class="form-horizontal" data-toggle="validator">
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
                            <label for="tipo_comprobante" class="control-label col-md-3 col-sm-3 col-xs-12">Tipo Comprobante *</label>
                          <div class="col-md-6">
                            <select name="tipo_comprobante" id="select2-tipos" class="form-control" style="width: 100%">
                                <option></option>
                                <option value="F">Factura</option>
                                <option value="N">Nota de Crédito</option>
                            </select>
                          </div>
                    </div>
                    <div class="form-group">
                            <label for="timbrado_id" class="control-label col-md-3 col-sm-3 col-xs-12">Nro Timbrado *</label>
                          <div class="col-md-6">
                            <select name="timbrado_id" id="select2-timbrados" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($timbrados as $id => $timbrado)
                                  <option value="{{ $timbrado->id }}">{{ $timbrado->nro_timbrado }}</option>
                                @endforeach
                            </select>
                          </div>
                    </div>
                    <div class="form-group">
                            <label for="sucursal_id" class="control-label col-md-3 col-sm-3 col-xs-12">Sucursal *</label>
                          <div class="col-md-6">
                            <select name="sucursal_id" id="select2-sucursales" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($sucursales as $id => $sucursal)
                                  <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                @endforeach
                            </select>
                          </div>
                    </div>
                    <div class="form-group">
                        <label for="nro_inicial" class="col-md-3 control-label">Nro Inicial *</label>
                        <div class="col-md-6">
                            <input type="number" id="nro_inicial" name="nro_inicial" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nro_final" class="col-md-3 control-label">Nro Final *</label>
                        <div class="col-md-6">
                            <input type="number" id="nro_final" name="nro_final" class="form-control">
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