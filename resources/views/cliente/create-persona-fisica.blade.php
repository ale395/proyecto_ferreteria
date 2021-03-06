<div class="modal fade" id="modal-form-fisica" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="cliente-form" method="post" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> &times; </span>
                    </button>
                    <h3 class="modal-title"></h3>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="tipo_persona_fisica" name="tipo_persona">
                    <div class="form-group">
                        <div id="error-block" class="alert alert-danger">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nro_cedula" class="col-md-2 control-label">Nro Cédula*</label>
                        <div class="col-md-3">
                            <input type="text" id="nro_cedula" name="nro_cedula" class="form-control" autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ruc" class="col-md-2 control-label">RUC</label>
                        <div class="col-md-4">
                            <input type="text" id="ruc" name="ruc" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nombre" class="col-md-2 control-label">Nombre*</label>
                        <div class="col-md-9">
                            <input type="text" id="nombre" name="nombre" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="apellido" class="col-md-2 control-label">Apellido*</label>
                        <div class="col-md-9">
                            <input type="text" id="apellido" name="apellido" class="form-control">
                            <span class="help-block with-errors"></span>
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