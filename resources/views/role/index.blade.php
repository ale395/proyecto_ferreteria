@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Roles
                        <!-- Verifica si el usuario tiene permiso para crear registros -->
                        @can('roles.create')
                          <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @endcan 
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="role-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    @include('role.form')
    
@endsection

@section('ajax_datatables')
	<script type="text/javascript">
      var table = $('#role-table').DataTable({
                      language: { url: 'datatables/translation/spanish' },
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.roles') }}",
                      columns: [
                        {data: 'slug', name: 'slug'},
                        {data: 'name', name: 'name'},
                        {data: 'description', name: 'description'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

      function addForm() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Nuevo Rol');
      }

      $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('roles') }}";
                    else url = "{{ url('roles') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        data : $('#modal-form form').serialize(),
                        success : function($data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                        },
                        error : function(){
                            alert('Ops! Algo salio mal!');
                        }
                    });
                    return false;
                }
            });
        });

      function editForm(id) {
        save_method = 'edit';
        $('input[name=_method]').val('PATCH');
        $('#modal-form form')[0].reset();
        $.ajax({
          url: "{{ url('roles') }}" + '/' + id + "/edit",
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Editar Rol');

            $('#id').val(data.id);
            $('#slug').val(data.slug);
            $('#name').val(data.name);
            $('#description').val(data.description);
          },
          error : function() {
              alert("No se encontraron datos");
          }
        });
      }

      function deleteData(id){
        $.confirm({
            title: 'What is up?',
            content: 'Here goes a little content',
            type: 'green',
            buttons: {   
                ok: {
                    text: "ok!",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function(){
                         console.log('the user clicked confirm');
                    }
                },
                cancel: function(){
                        console.log('the user clicked cancel');
                }
            }
        });
      }

      /*function deleteData(id){
        swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this imaginary file!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            swal("Poof! Your imaginary file has been deleted!", {
              icon: "success",
            });
          } else {
            swal("Your imaginary file is safe!");
          }
        });
    }*/

    </script>
@endsection

@section('otros_scripts')
  <script type="text/javascript">
    $('#modal-form').on('shown.bs.modal', function() {
      $("#slug").focus();
    });
  </script>
@endsection



