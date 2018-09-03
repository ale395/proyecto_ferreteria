<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="cotizacion-form" method="post" class="form-horizontal" data-toggle="validator">
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
                        <label for="fecha_cotizacion" class="col-md-2 control-label">Fecha *</label>
                        <div class="col-md-3">
                            <input type="text" id="fecha_cotizacion" name="fecha_cotizacion" class="form-control dpfechacotizacion" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'">
                            <span class="help-block with-errors"></span>
                        </div>

                    </div>


                    <div class="form-group">
                        <label for="valor_compra" class="col-md-2 control-label">Valor Compra</label>
                        <div class="col-md-4">
                            <input type="text" id="valor_compra" name="valor_compra" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>

                        <label for="valor_venta" class="col-md-2 control-label">Valor Venta</label>
                        <div class="col-md-4">
                            <input type="text" id="valor_venta" name="valor_venta" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                    <label for="moneda_id" class="control-label col-md-2">Moneda *</label>
                        <div class="col-md-4">
                            <select name="moneda_id" id="select2-monedas" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($monedas as $id => $moneda)
                                  <option value="{{ $moneda->id }}">{{ $moneda->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                   

                </div>

                <div class="modal-footer">
                    <button id="form-btn-guardar" type="submit" class="btn btn-primary btn-save">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>

            </form>
        </div>
    </div>
</div>