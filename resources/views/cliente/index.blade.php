@extends('home')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Clientes
                        @can('clientes.create')
                          <a data-toggle="tooltip" data-placement="top" title="Nuevo cliente" onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @endcan
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="cliente-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th width="100">Tipo Persona</th>
                                <th>Nombre</th>
                                <th>Tipo Documento</th>
                                <th>Nro Documento</th>
                                <th width="40">Activo</th>
                                <th width="110">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('cliente.create-persona-fisica')
    @include('cliente.create-persona-juridica')
@endsection

@section('ajax_datatables')
  <script type="text/javascript">
    var table = $('#cliente-table').DataTable({
        language: { url: '/datatables/translation/spanish' },
        processing: true,
        serverSide: true,
        ajax: "{{ route('api.clientes') }}",
        columns: [
            {data: 'tipo_persona', name: 'tipo_persona'},
            {data: 'nombre', name: 'nombre'},
            {data: 'tipo_documento', name: 'tipo_documento'},
            {data: 'nro_documento', name: 'nro_documento'},
            {data: 'activo', name: 'activo'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

    $('#cliente-table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })

    function addForm() {
        $.confirm({
            title: 'Tipo de Persona',
            content: 'Por favor seleccione el tipo de persona a registrar',
            type: 'blue',
            backgroundDismiss: true,
            theme: 'modern',
            buttons: {
                confirm: {
                    text: "Física",
                    btnClass: 'btn-blue',
                    action: function(){
                        save_method = "add";
                        $('#error-block').hide();
                        $('input[name=_method]').val('POST');
                        $('#tipo_persona_fisica').val('F');
                        $('#modal-form-fisica').modal('show');
                        $('#modal-form-fisica form')[0].reset();
                        $('.modal-title').text('Nuevo Cliente - Persona Física');
                    }
                },
                cancel: {
                    text: "Jurídica",
                    btnClass: 'btn-default',
                    action: function(){
                        save_method = "add";
                        $('#error-block-juridica').hide();
                        $('input[name=_method]').val('POST');
                        $('#tipo_persona_juridica').val('J');
                        $('#modal-form-juridica').modal('show');
                        $('#modal-form-juridica form')[0].reset();
                        $('.modal-title').text('Nuevo Cliente - Persona Jurídica');
                    }
                }
            }
        });
    }

    function showForm(id) {
        var show_page = '/clientes/' + id;
        window.location.href = show_page;
    }

      $(function(){
            $('#modal-form-fisica form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('clientes') }}";
                    else url = "{{ url('clientes') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        data : $('#modal-form-fisica form').serialize(),
                        success : function($data) {
                            $('#modal-form-fisica').modal('hide');
                            table.ajax.reload();
                            var obj = $.alert({
                                title: 'Información',
                                content: 'Cliente guardado correctamente!',
                                icon: 'fa fa-check',
                                type: 'green',
                                backgroundDismiss: true,
                                theme: 'modern',
                            });
                            setTimeout(function(){
                                obj.close();
                            },4000); 
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
                              theme: 'modern',
                            });
                          }
                            
                        }
                    });
                    return false;
                }
            });
        });

      $(function(){
            $('#modal-form-juridica form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('clientes') }}";
                    else url = "{{ url('clientes') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        data : $('#modal-form-juridica form').serialize(),
                        success : function($data) {
                            $('#modal-form-juridica').modal('hide');
                            table.ajax.reload();
                            var obj = $.alert({
                                title: 'Información',
                                content: 'Cliente guardado correctamente!',
                                icon: 'fa fa-check',
                                type: 'green',
                                backgroundDismiss: true,
                                theme: 'modern',
                            });
                            setTimeout(function(){
                                obj.close();
                            },4000);
                        },
                        error : function(data){
                            var errors = '';
                            var errores = '';
                            if(data.status === 422) {
                                var errors = data.responseJSON;
                                $.each(data.responseJSON.errors, function (key, value) {
                                    errores += '<li>' + value + '</li>';
                                });
                                $('#error-block-juridica').show().html(errores);
                            }else{
                                $.alert({
                                  title: 'Atención!',
                                  content: 'Ocurrió un error durante el proceso!',
                                  icon: 'fa fa-times-circle-o',
                                  type: 'red',
                                  theme: 'modern',
                                });
                          }
                            
                        }
                    });
                    return false;
                }
            });
        });

    function editForm(id){
        var edit_page = '/clientes/' + id + '/edit';
        window.location.href = edit_page;
    }

      function deleteData(id){
        $.confirm({
            title: '¿De verdad lo quieres eliminar?',
            content: 'No podrás volver atras',
            type: 'red',
            theme: 'modern',
            buttons: {   
                ok: {
                    text: "Eliminar",
                    btnClass: 'btn-danger',
                    keys: ['enter'],
                    action: function(){
                          var csrf_token = $('meta[name="csrf-token"]').attr('content');
                          
                              $.ajax({
                                  url : "{{ url('clientes') }}" + '/' + id,
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
                                              theme: 'modern',
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
    $('#modal-form-fisica').on('shown.bs.modal', function() {
      $("#nro_cedula").focus();
    });

    $('#modal-form-juridica').on('shown.bs.modal', function() {
      $("#ruc_juridica").focus();
    });
  </script>
  
  <script type="text/javascript">
    $('#cliente-form').validator().off('input.bs.validator change.bs.validator focusout.bs.validator');
    $('#cliente-form-juridica').validator().off('input.bs.validator change.bs.validator focusout.bs.validator');
    $("#nro_cedula").on({
            "focus": function (event) {
                $(event.target).select();
            },
            "keyup": function (event) {
                $(event.target).val(function (index, value ) {
                    return value.replace(/\D/g, "")
                                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                });
            }
    });
    $("#ruc").on({
        "focus": function (event) {
            $(event.target).select();
        },
        "keyup": function (event) {
            $(event.target).val(function (index, value ) {
                return value.replace(/\D/g, "")
                            .replace(/([0-9])([0-9]{1})$/, '$1-$2');
            });
        }
    });
    $("#ruc_juridica").on({
        "focus": function (event) {
            $(event.target).select();
        },
        "keyup": function (event) {
            $(event.target).val(function (index, value ) {
                return value.replace(/\D/g, "")
                            .replace(/([0-9])([0-9]{1})$/, '$1-$2');
            });
        }
    });
  </script>
@endsection
