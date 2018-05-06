<div class="modal" id="modal-form-create" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> &times; </span>
                    </button>
                    <h3 class="modal-title"></h3>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id-create" name="id">
                    <div class="form-group">
                        <label for="name" class="col-md-3 control-label">Nombre</label>
                        <div class="col-md-6">
                            <input type="text" id="name-create" name="name" class="form-control" autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="name" class="col-md-3 control-label">Correo</label>
                      <div class="col-md-6">
                          <input type="email" id="email-create" name="email" class="form-control" required>
                          <span class="help-block with-errors"></span>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="name" class="col-md-3 control-label">Contraseña</label>
                      <div class="col-md-6">
                          <input type="password" id="password-create" name="password" class="form-control" required>
                          <span class="help-block with-errors"></span>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="name" class="col-md-3 control-label">Rol</label>
                      <div class="col-md-6">
                          <select class="form-control js-role" name="role_id" style="width: 100%">
                            @foreach($roles as $role)
                              <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                          </select>
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