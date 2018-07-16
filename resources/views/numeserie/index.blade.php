@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Rangos de Numeración de Series
                        <!-- Verifica si el usuario tiene permiso para crear registros -->
                        @can('numeseries.create')
                          <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;">Nuevo Registro</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;">Nuevo Registro</a>
                        @endcan 
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="numeseri-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Concepto</th>
                                <th>Serie</th>
                                <th>Número Inicial</th>
                                <th>Número Final</th>
                                <th>Estado</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        @include('numeserie.form')
    
@endsection

@section('ajax_datatables')
	<script type="text/javascript">
      var table = $('#numeseri-table').DataTable({
                      language: { url: 'datatables/translation/spanish' },
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.numeSeries') }}",
                      columns: [
                        {data: 'concepto', name: 'concepto'},
                        {data: 'serie', name: 'serie'},
                        {data: 'nro_inicial', name: 'nro_inicial'},
                        {data: 'nro_final', name: 'nro_final'},
                        {data: 'nomb_estado', name: 'nomb_estado'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

      function addForm() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Nuevo Registro');
      }

      $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('numeSeries') }}";
                    else url = "{{ url('numeSeries') . '/' }}" + id;

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

      $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('numeSeries') }}";
                    else url = "{{ url('numeSeries') . '/' }}" + id;

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
          url: "{{ url('numeSeries') }}" + '/' + id + "/edit",
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Editar Registro');

            $('#id').val(data.id);
            $('#concepto_id').val(data.concepto_id);
            $('#serie_id').val(data.serie_id);
            $('#nro_inicial').val(data.nro_inicial);
            $('#nro_final').val(data.nro_final);
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
                    url : "{{ url('numeSeries') }}" + '/' + id,
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
          $('.js-concepto').select2({
            dropdownParent: $('#modal-form')
          });
      });

      $(document).ready(function() {
          $('.js-serie').select2({
            dropdownParent: $('#modal-form')
          });
      });
    </script>
    
@endsection