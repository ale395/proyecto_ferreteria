@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Conceptos
                        <!-- Verifica si el usuario tiene permiso para crear registros -->
                        @can('conceptos.create')
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
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Módulo</th>
                                <th>Tipo Concepto</th>
                                <th>Afecta Stock</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    @include('concepto.form')
    
@endsection

@section('ajax_datatables')
	<script type="text/javascript">
      var table = $('#concepto-table').DataTable({
                      language: { url: 'datatables/translation/spanish' },
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.tconceptos') }}",
                      columns: [
                        {data: 'concepto', name: 'concepto'},
                        {data: 'nombre_concepto', name: 'nombre_concepto'},
                        {data: 'modulo', name: 'modulo'},
                        {data: 'tipo_concepto', name: 'tipo_concepto'},
                        {data: 'muev_stock', name: 'muev_stock'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

      function addForm() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Nuevo Concepto');
      }

      $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('tconceptos') }}";
                    else url = "{{ url('tconceptos') . '/' }}" + id;

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
          url: "{{ url('tconceptos') }}" + '/' + id + "/edit",
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Editar Concepto');

            $('#id').val(data.id);
            $('#concepto').val(data.concepto);
            $('#nombre_concepto').val(data.nombre_concepto);
            $('#modulo_id').val(data.modulo_id);
            $('#tipo_concepto').val(data.tipo_concepto);
            $('#muev_stock').val(data.muev_stock);
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
                    url : "{{ url('tconceptos') }}" + '/' + id,
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

@section('otros_scripts')
  <script>
    $(document).ready(function() {
        $('.js-modulo').select2({
          dropdownParent: $('#modal-form')
        });
    });
  </script>
@endsection