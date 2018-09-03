@extends('home')

@section('content')

  <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Cotizaciones
                        @can('cotizaciones.create')
                          <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @endcan
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="cotizacion-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th width="50">Fecha</th>
                                <th>Valor de compra</th>
                                <th>Valor de venta</th>
                                <th>Moneda</th>
                                <th width="110">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    @include('cotizacion.form')
@endsection

@section('ajax_datatables')
  <script type="text/javascript">
      var table = $('#cotizacion-table').DataTable({
                      language: { url: 'datatables/translation/spanish' },
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.cotizaciones') }}",
                      columns: [
                        {data: 'fecha_cotizacion', name: 'fecha_cotizacion'},
                        {data: 'valor_compra', name: 'valor_compra'},
                        {data: 'valor_venta', name: 'valor_venta'},
                        {data: 'moneda', name: 'moneda'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

      function addForm() {
        save_method = "add";
        $('#error-block').hide();

        

        $('#select2-monedas').val("").change();
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Nueva Cotizacion');

        $('#fecha_cotizacion').prop('readonly', false);
        $('#valor_venta').prop('readonly', false);
        $('#valor_compra').prop('readonly', false);
        $('#select2-monedas').prop('disabled', false);
        $('#form-btn-guardar').prop('disabled', false);
      }

      function showForm(id) {
        save_method = "add";
        $('#error-block').hide();
        $('input[name=_method]').val('GET');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Ver Cotizacion');

        $.ajax({
          url: "{{ url('cotizaciones') }}" + '/' + id,
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');

            $('#id').val(data.id);
            $('#fecha_cotizacion').val(data.fecha_cotizacion);
            $('#valor_compra').val(data.valor_compra);
            $('#valor_venta').val(data.valor_venta);
            $("#select2-monedas").select2("val", "");
            $('#select2-monedas').val(data.moneda_id).change();
           

            $('#fecha_cotizacion').prop('readonly', true);
            $('#valor_compra').prop('readonly', true);
            $('#valor_venta').prop('readonly', true);

            $('#select2-monedas').prop('disabled', true);

            $('#form-btn-guardar').prop('disabled', true);
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

      $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('cotizaciones') }}";
                    else url = "{{ url('cotizaciones') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        data : $('#modal-form form').serialize(),
                        success : function($data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                        },
                        error : function(data){
                            var errors = '';
                            var errores = '';
                            if(data.status === 422) {
                                var errors = data.responseJSON;
                                $.each(data.responseJSON.errors, function (key, value) {
                                    errores += '<li>' + value + '</li>';
                                });
                                $('#error-block').show().html(errores);
                            }else{
                              $.alert({
                              title: 'Atención!',
                              content: 'Ocurrió un error durante el proceso!',
                              icon: 'fa fa-times-circle-o',
                              type: 'red',
                            });
                          }
                            
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
        $('#error-block').hide();
        $.ajax({
          url: "{{ url('cotizaciones') }}" + '/' + id + "/edit",
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Editar Cotizaciones');

            $('#fecha_cotizacion').prop('readonly', false);
            $('#valor_compra').prop('readonly', false);
            $('#valor_venta').prop('readonly', false);

            $('#select2-monedas').prop('disabled', false);

            $('#form-btn-guardar').prop('disabled', false);

            $('#id').val(data.id);
            $('#fecha_cotizacion').val(data.fecha_cotizacion);
            $('#valor_compra').val(data.valor_compra);
            $('#valor_venta').val(data.valor_venta);


            $("#select2-monedas").select2("val", "");
            $('#select2-monedas').val(data.moneda_id).change();

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
                                  url : "{{ url('cotizaciones') }}" + '/' + id,
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
    $('#cotizacion-form').validator().off('input.bs.validator change.bs.validator focusout.bs.validator');
  </script>

  <script type="text/javascript">
    $(document).ready(function(){
            $('#select2-monedas').select2({
                placeholder : 'Seleccione una de las opciones',
                tags: false,
                width: 'resolve',
                dropdownParent: $('#modal-form'),
                language: "es"
            });
        });
  </script>
<script type="text/javascript">
    $(function() {
      $('.dpfechacotizacion').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        todayBtn: true,
        todayHighlight: true,
        autoclose: true
      });
      $('#fecha_cotizacion').click(function(e){
                e.stopPropagation();
                $('.dpfechacotizacion').datepicker('update');
            });  
    });
  </script>
@endsection
