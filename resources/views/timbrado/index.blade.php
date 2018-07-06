@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Timbrados
                        <!-- Verifica si el usuario tiene permiso para crear registros -->
                        @can('timbrados.create')
                          <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;">Nuevo Timbrado</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;">Nuevo Timbrado</a>
                        @endcan 
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="timbrado-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Número de Timbrado</th>
                                <th>Fecha Vigencia</th>
                                <th>Estado</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    @include('timbrado.form')
    
@endsection

@section('ajax_datatables')
	<script type="text/javascript">
      var table = $('#timbrado-table').DataTable({
                      language: { url: 'datatables/translation/spanish' },
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.timbrados') }}",
                      columns: [
                        {data: 'nro_timbrado', name: 'nro_timbrado'},
                        {data: 'fecha_vigencia', name: 'fecha_vigencia'},
                        {data: 'nomb_estado', name: 'nomb_estado'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

      function addForm() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Nuevo Timbrado');
      }

      $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('timbrados') }}";
                    else url = "{{ url('timbrados') . '/' }}" + id;

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
          url: "{{ url('timbrados') }}" + '/' + id + "/edit",
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Editar Timbrado');

            $('#id').val(data.id);
            $('#nro_timbrado').val(data.nro_timbrado);
            $('#fecha_vigencia').val(data.fecha_vigencia);
            $('#estado').val(data.estado);
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
                    url : "{{ url('timbrados') }}" + '/' + id,
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