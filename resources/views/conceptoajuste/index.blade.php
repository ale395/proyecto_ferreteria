@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Conceptos de Ajuste
                        @can('conceptoajuste.create')
                          <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;">Nuevo Concepto</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;">Nuevo Concepto</a>
                        @endcan
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="concepto-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    @include('conceptoajuste.form')
@endsection

@section('ajax_datatables')
	<script type="text/javascript">
      var table = $('#concepto-table').DataTable({
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.conceptos') }}",
                      columns: [
                        {data: 'num_concepto', name: 'num_concepto'},
                        {data: 'descripcion', name: 'descripcion'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

      function addForm() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Nuevo Concepto de Ajuste');
      }

      $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('conceptos') }}";
                    else url = "{{ url('conceptos') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        data : $('#modal-form form').serialize(),
                        success : function($data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                        },
                        error : function(){
                            alert('Ha ocurrido un error, favor verificar');
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
          url: "{{ url('conceptos') }}" + '/' + id + "/edit",
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Editar Concepto de Ajuste');

            $('#id').val(data.id);
            $('#num_concepto').val(data.num_concepto);
            $('#descripcion').val(data.descripcion);
          },
          error : function() {
              alert("Nothing Data");
          }
        });
      }

      function deleteData(id){
            var popup = confirm("desea eliminar el registro?");
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            if (popup == true ){
                $.ajax({
                    url : "{{ url('conceptos') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                    },
                    error : function () {
                        alert("Ha ocurrido un error, favor verificar");
                    }
                })
            }
        }

    </script>
@endsection
