<div class="modal fade" id="modal-form-factura" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="anulacion-factura-form" method="post" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> &times; </span>
                    </button>
                    <h3 class="modal-title"></h3>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="tipo_comprobante_fact" name="tipo_comprobante">
                    <input type="hidden" id="comprobante_id_fact" name="comprobante_id">
                    <input type="hidden" id="fecha_anulacion_fact" name="fecha_anulacion" value="{{$fecha_actual}}">
                    <div class="form-group">
                        <div id="error-block" class="alert alert-danger"></div>
                    </div>
                    <div class="form-group">
                            <label for="motivo_anulacion_id" class="control-label col-md-3 col-sm-3 col-xs-12">Motivo*</label>
                          <div class="col-md-6">
                            <select name="motivo_anulacion_id" id="select2-motivos-fact" class="form-control" style="width: 100%">
                            </select>
                          </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-save">Anular</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>