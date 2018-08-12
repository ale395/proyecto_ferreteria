@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Timbrados
                        <!-- Verifica si el usuario tiene permiso para crear registros -->
                        @can('timbrados.create')
                          <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @endcan 
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="timbrado-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Número de Timbrado</th>
                                <th>Fecha Inicio Vigencia</th>
                                <th>Fecha Fin Vigencia</th>
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
                        {data: 'fecha_inicio_vigencia', name: 'fecha_inicio_vigencia'},
                        {data: 'fecha_fin_vigencia', name: 'fecha_fin_vigencia'},
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
                            $.alert({
                              title: 'Atención!',
                              content: 'Ocurrió un error durante el proceso!',
                              icon: 'fa fa-times-circle-o',
                              type: 'red',
                            });
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
            $('#fecha_inicio_vigencia').val(data.fecha_inicio_vigencia);
            $('#fecha_fin_vigencia').val(data.fecha_fin_vigencia);
          },
          error : function() {
              $.alert({
                title: 'Atención!',
                content: 'No se encontraron datos!',
                icon: 'fa fa-exclamation-circle',
                type: 'orange',
              });
          }
        });
      }

      function deleteData(id){
          $.confirm({
            title: '¿De verdad lo quieres eliminar?',
            content: 'No podrás volver atras',
            type: 'red',
            buttons: {   
                ok: {
                    text: "Eliminar",
                    btnClass: 'btn-danger',
                    keys: ['enter'],
                    action: function(){
                          var csrf_token = $('meta[name="csrf-token"]').attr('content');
                          
                              $.ajax({
                                  url : "{{ url('timbrados') }}" + '/' + id,
                                  type : "POST",
                                  data : {'_method' : 'DELETE', '_token' : csrf_token},
                                  success : function(data) {
                                      table.ajax.reload();
                                  },
                                  error : function () {
                                          $.alert({
                                              title: 'Atención!',
                                              content: 'Ocurrió un error durante el proceso!',
                                              icon: 'fa fa-times-circle-o',
                                              type: 'red',
                                          });
                                  }
                              })
                    }
                },
                cancel: function(){
                        console.log('Cancel');
                }
            }
        });
        }

    </script>
@endsection

@section('otros_scripts')
  <script type="text/javascript">
    $('#modal-form').on('shown.bs.modal', function() {
      $("#nro_timbrado").focus();
    });
  </script>
  
  <script type="text/javascript">
    $('#timbrado-form').validator().off('input.bs.validator change.bs.validator focusout.bs.validator')
  </script>

  <script type="text/javascript">
    $(function() {
      $('.dpiniciovigencia').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        todayBtn: true,
        todayHighlight: true
      });
      $('#fecha_inicio_vigencia').click(function(e){
                e.stopPropagation();
                $('.dpiniciovigencia').datepicker('update');
            });  
    });
  </script>

  <script type="text/javascript">
    $(function() {
      $('.dpfinvigencia').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        todayBtn: true,
        todayHighlight: true
      });
      $('#fecha_fin_vigencia').click(function(e){
                e.stopPropagation();
                $('.dpfinvigencia').datepicker('update');
            });  
    });
  </script>
@endsection
