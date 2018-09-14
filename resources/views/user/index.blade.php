@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Usuarios
                        <!-- Verifica si el usuario tiene permiso para crear registros -->
                        @can('users.create')
                          <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;">Nuevo Usuario</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;">Nuevo Usuario</a>
                        @endcan 
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="user-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    @include('user.form-create')

    @include('user.form-edit')
    
@endsection

@section('ajax_datatables')
	<script type="text/javascript">
      var table = $('#user-table').DataTable({
                      language: { url: 'datatables/translation/spanish' },
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.users') }}",
                      columns: [
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'role', name: 'role'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

      function addForm() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-form-create').modal('show');
        $('#modal-form-create form')[0].reset();
        $('.modal-title').text('Nuevo Usuario');
      }

      $(function(){
            $('#modal-form-create form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('users') }}";
                    else url = "{{ url('users') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        data : $('#modal-form-create form').serialize(),
                        success : function($data) {
                            $('#modal-form-create').modal('hide');
                            table.ajax.reload();
                        },
                        error : function(){
                            alert('Oops! Something Error!');
                        }
                    });
                    return false;
                }
            });
        });

      $(function(){
            $('#modal-form-edit form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('users') }}";
                    else url = "{{ url('users') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        data : $('#modal-form-edit form').serialize(),
                        success : function($data) {
                            $('#modal-form-edit').modal('hide');
                            table.ajax.reload();
                        },
                        error : function(){
                            alert('Oops! Something Error!');
                        }
                    });
                    return false;
                }
            });
        });

      function editForm(id) {
        save_method = 'edit';
        $('input[name=_method]').val('PATCH');
        $('#modal-form-edit form')[0].reset();
        $.ajax({
          url: "{{ url('users') }}" + '/' + id + "/edit",
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form-edit').modal('show');
            $('.modal-title').text('Editar Usuario');

            $('#id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#password').val(data.password);
            $('#role_id').val(data.role_id);
          },
          error : function() {
              alert("Nothing Data");
          }
        });
      }

      function deleteData(id){
            var popup = confirm("Are you sure for delete this data ?");
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            if (popup == true ){
                $.ajax({
                    url : "{{ url('users') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                    },
                    error : function () {
                        alert("Oops! Something Wrong!");
                    }
                })
            }
        }

    </script>

    <script>
      $(document).ready(function() {
          $('.js-role').select2({
            dropdownParent: $('#modal-form-create')
          });
      });

      $(document).ready(function() {
          $('.js-role-edit').select2({
            dropdownParent: $('#modal-form-edit')
          });
      });
    </script>
    
@endsection