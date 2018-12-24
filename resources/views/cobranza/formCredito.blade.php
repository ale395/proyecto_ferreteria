<div class="modal fade" id="modal-cuenta-credito" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="credito-form" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> &times; </span>
                    </button>
                    <h3 class="modal-title"></h3>
                </div>

                <div class="modal-body">
                    <table id="tabla-cuenta-credito" class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="16%">Nro Factura</th>
                                <th class="text-center" width="13%">Fecha</th>
                                <th class="text-center" width="13%">Total</th>
                                <th>Comentario</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <label for="monto_total" class="col-md-4 control-label">Monto Total*</label>
                    <div class="col-md-3">
                        <input type="text" id="monto_total" name="monto_total" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="form-btn-guardar" class="btn btn-primary btn-save" onclick="cargarFacturasCredito()">Aceptar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>