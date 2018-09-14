@extends('home')

@section('content')

  <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Proveedores
                        @can('proveedores.create')
                          <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @endcan
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="proveedor-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th width="50">Codigo</th>
                                <th>Nombre</th>
                                <th>Apellido/Razón Social</th>
                                <th width="80">Nro CI</th>
                                <th>RUC</th>
                                <th width="40">Activo</th>
                                <th width="110">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    @include('proveedores.form')
@endsection

@section('ajax_datatables')
  <script type="text/javascript">
      var table = $('#proveedor-table').DataTable({
                      language: { url: 'datatables/translation/spanish' },
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.proveedores') }}",
                      columns: [
                        {data: 'codigo', name: 'codigo'},
                        {data: 'nombre', name: 'nombre'},
                        {data: 'razon_social', name: 'razon_social'},
                        {data: 'nro_documento', name: 'nro_documento'},
                        {data: 'ruc', name: 'ruc'},
                        {data: 'activo', name: 'activo'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

      function addForm() {
        save_method = "add";
        $('#error-block').hide();
        $('#activo').attr('checked', true);
        
        $('#select2-tipos').val("").change();
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Nuevo Proveedor');

        $('#codigo').prop('readonly', false);
        $('#nombre').prop('readonly', false);
        $('#razon_social').prop('readonly', false);
        $('#direccion').prop('readonly', false);
        $('#telefono').prop('readonly', false);
        $('#nro_documento').prop('readonly', false);
        $('#ruc').prop('readonly', false);
        $('#correo_electronico').prop('readonly', false);
        $('#select2-tipos').prop('disabled', false);
        $('#activo').prop('disabled', false);
        $('#form-btn-guardar').prop('disabled', false);
      }

      function showForm(id) {
        save_method = "add";
        $('#error-block').hide();
        $('input[name=_method]').val('GET');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Ver Proveedor');

        $.ajax({
          url: "{{ url('proveedores') }}" + '/' + id,
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');            
  
            $('#id').val(data.id);
            $('#codigo').val(data.codigo);
            $('#nombre').val(data.nombre);
            $('#razon_social').val(data.razon_social);
            $('#direccion').val(data.direccion);
            $('#telefono').val(data.telefono);
            $('#nro_documento').val(data.nro_documento);
            $('#ruc').val(data.ruc);
            $('#correo_electronico').val(data.correo_electronico);
            $("#select2-tipos").select2("val", "");
            $('#select2-tipos').val(data.tipo_cliente_id).change();
            if (data.activo) {
              $('#activo').attr('checked', true);
            }else{
              $('#activo').attr('checked', false);
            }

            $('#codigo').prop('readonly', true);
            $('#nombre').prop('readonly', true);
            $('#razon_social').prop('readonly', true);
            $('#direccion').prop('readonly', true);
            $('#telefono').prop('readonly', true);
            $('#nro_documento').prop('readonly', true);
            $('#ruc').prop('readonly', true);
            $('#correo_electronico').prop('readonly', true);
            $('#select2-tipos').prop('disabled', true);
            $('#activo').prop('disabled', true);
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
                    if (save_method == 'add') url = "{{ url('proveedores') }}";
                    else url = "{{ url('proveedores') . '/' }}" + id;

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
          url: "{{ url('proveedores') }}" + '/' + id + "/edit",
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Editar Proveedor');

            $('#codigo').prop('readonly', false);
            $('#nombre').prop('readonly', false);
            $('#razon_social').prop('readonly', false);
            $('#direccion').prop('readonly', false);
            $('#telefono').prop('readonly', false);
            $('#nro_documento').prop('readonly', false);
            $('#ruc').prop('readonly', false);
            $('#correo_electronico').prop('readonly', false);
            $('#select2-tipos').prop('disabled', false);
            $('#activo').prop('disabled', false);
            $('#form-btn-guardar').prop('disabled', false);

            $('#id').val(data.id);
            $('#codigo').val(data.codigo);
            $('#nombre').val(data.nombre);
            $('#razon_social').val(data.razon_social);
            $('#direccion').val(data.direccion);
            $('#telefono').val(data.telefono);
            $('#nro_documento').val(data.nro_documento);
            $('#ruc').val(data.ruc);
            $('#correo_electronico').val(data.correo_electronico);
            $("#select2-tipos").select2("val", "");
            $('#select2-tipos').val(data.tipo_cliente_id).change();
            if (data.activo) {
              $('#activo').attr('checked', true);
            }else{
              $('#activo').attr('checked', false);
            }
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
                                  url : "{{ url('proveedores') }}" + '/' + id,
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
      $("#codigo").focus();
    });
  </script>
  
  <script type="text/javascript">
    $('#cliente-form').validator().off('input.bs.validator change.bs.validator focusout.bs.validator');
  </script>

  <!-- Comentamos esto porque no es obligatorio completar el campo
  <script type="text/javascript">
    $(document).ready(function(){
            $('#select2-tipos').select2({
                placeholder : 'Seleccione una de las opciones',
                tags: false,
                width: 'resolve',
                dropdownParent: $('#modal-form'),
                language: "es"
            });
        });
  </script>
-->

@endsection
