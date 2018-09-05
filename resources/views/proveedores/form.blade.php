<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
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
                    <div class="form-group">
                        <div id="error-block" class="alert alert-danger">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="codigo" class="col-md-2 control-label">Código *</label>
                        <div class="col-md-3">
                            <input type="text" id="codigo" name="codigo" class="form-control" autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nombre" class="col-md-2 control-label">Nombre *</label>
                        <div class="col-md-9">
                            <input type="text" id="nombre" name="nombre" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="razon_social" class="col-md-2 control-label">Apellido/Razón Social</label>
                        <div class="col-md-9">
                            <input type="text" id="razon_social" name="razon_social" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nro_documento" class="col-md-2 control-label">Nro Cédula</label>
                        <div class="col-md-4">
                            <input type="number" id="nro_documento" name="nro_documento" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>

                        <label for="ruc" class="col-md-1 control-label">RUC</label>
                        <div class="col-md-4">
                            <input type="text" id="ruc" name="ruc" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="direccion" class="col-md-2 control-label">Dirección</label>
                        <div class="col-md-9">
                            <input type="text" id="direccion" name="direccion" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telefono" class="col-md-2 control-label">Nro Teléfono</label>
                        <div class="col-md-4">
                            <input type="text" id="telefono" name="telefono" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>

                        <label for="correo_electronico" class="col-md-1 control-label">Email</label>
                        <div class="col-md-4">
                            <input type="email" id="correo_electronico" name="correo_electronico" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo_cliente_id" class="control-label col-md-2">Tipo de Proveedor</label>
                        <div class="col-md-4">
                            <select name="tipo_proveedor_id" id="select2-tipos" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($tipos_proveedores as $id => $tipo_proveedor)
                                  <option value="{{ $tipo_proveedor->id }}">{{ $tipo_proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Activo *</label>
                        <div class="col-md-1 custom-control custom-checkbox">
                            <input type="hidden" name="activo" value="false">
                            <input id="activo" type="checkbox" class="custom-control-input" name="activo" value="true">
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