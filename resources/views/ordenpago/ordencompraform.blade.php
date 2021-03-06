<div class="modal fade" id="modal-pedido-venta" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="pedido-form" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> &times; </span>
                    </button>
                    <h3 class="modal-title"></h3>
                </div>

                <div class="modal-body">
                    <table id="tabla-pedidos" class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th width="13%">Nro Orden Compra</th>
                                <th width="13%">Fecha</th>
                                <th width="13%">Moneda</th>
                                <th width="13%">Total</th>
                                <th>Comentario</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" id="form-btn-guardar" class="btn btn-primary btn-save" onclick="cargarPedidos()">Cargar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>

            </form>
        </div>
    </div>
</div>