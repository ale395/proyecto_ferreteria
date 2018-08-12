<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="timbrado-form" method="post" class="form-horizontal" data-toggle="validator">
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
                            <input type="number" id="nro_timbrado" name="nro_timbrado" class="form-control" autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-md-3 control-label">Fecha Inicio Vigencia</label>
                        <div class="col-md-6">
                            <input type="text" id="fecha_inicio_vigencia" name="fecha_inicio_vigencia" class="form-control dpiniciovigencia" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="name" class="col-md-3 control-label">Fecha Fin Vigencia</label>
                        <div class="col-md-6">
                            <input type="text" id="fecha_fin_vigencia" name="fecha_fin_vigencia" class="form-control dpfinvigencia" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" required>
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