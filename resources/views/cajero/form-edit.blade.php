<div class="modal" id="modal-form-edit" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
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
                        <label for="num_cajero" class="col-md-3 control-label">Codigo</label>
                        <div class="col-md-6">
                            <input type="text" id="num_cajero-create" name="num_cajero" class="form-control" autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="descripcion" class="col-md-3 control-label">Descripcion</label>
                      <div class="col-md-6">
                          <input type="text" id="descripcion-create" name="descripcion" class="form-control" required>
                          <span class="help-block with-errors"></span>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="id_usuario" class="col-md-3 control-label">Usuario</label>
                      <div class="col-md-6">
                          <select class="form-control js-user" name="id_usuario" style="width: 100%">
                            @foreach($users as $user)
                              <option value="{{$user->id}}">{{$user->name}}</option>
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