@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Paises
                        <!-- Verifica si el usuario tiene permiso para crear registros -->
                        @can('paises.create')
                          <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;">Nuevo Pais</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;">Nuevo Pais</a>
                        @endcan
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="pais-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Descripcion</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    @include('pais.form')
    
@endsection

@section('ajax_datatables')
	<script type="text/javascript">
      var table = $('#pais-table').DataTable({
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.paises') }}",
                      columns: [
                        {data: 'descripcion', name: 'descripcion'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

      function addForm() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Nuevo Pais');
      }

      $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('paises') }}";
                    else url = "{{ url('paises') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        data : $('#modal-form form').serialize(),
                        success : function($data) {
                            $('#modal-form').modal('hide');
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
        $('#modal-form form')[0].reset();
        $.ajax({
          url: "{{ url('paises') }}" + '/' + id + "/edit",
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Editar Pais');

            $('#id').val(data.id);
            $('#descripcion').val(data.descripcion);
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
                    url : "{{ url('paises') }}" + '/' + id,
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
@endsection