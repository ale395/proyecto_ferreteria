<div class="modal fade" id="modal-cantidad-devo" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="cantidad-devo-form" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> &times; </span>
                    </button>
                    <h3 class="modal-title"></h3>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <div id="error-block-cant" class="alert alert-danger">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cant_total" class="col-md-5 control-label">Cantidad facturada</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="cant_total" name="cant_total" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cant_devo" class="col-md-5 control-label">Cantidad devuelta</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="cant_devo" name="cant_devo">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label">Tab Index</label>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="tab_index" name="tab_index">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="form-btn-guardar" class="btn btn-primary btn-save" onclick="cargarCantidad()">Aceptar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>

            </form>
        </div>
    </div>
</div>