@extends('home')

@section('content')

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Clasificacion de Clientes
                        @can('clasificacioncliente.create')
                          <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;"> Agregar</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"> Agregar</a>
                        @endcan
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="clasif-cliente-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    @include('clasificacioncliente.form')
@endsection

@section('ajax_datatables')
	<script type="text/javascript">
      var table = $('#clasif-cliente-table').DataTable({
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.clasificacionclientes') }}",
                      columns: [
                        {data: 'codigo', name: 'codigo'},
                        {data: 'nombre', name: 'nombre'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

      function addForm() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Nuevo tipo de Cliente');
      }

      $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('clasificacionclientes') }}";
                    else url = "{{ url('clasificacionclientes') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        data : $('#modal-form form').serialize(),
                        success : function($data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                        },
                        error : function(){
                            //alert('Ha ocurrido un error, favor verificar');
                            var msg = '';
                            if (jqXHR.status === 0) {
                                msg = 'Sin Conexion.\n Verifique la red.';
                            } else if (jqXHR.status == 404) {
                                msg = 'Pagina no encontradad.[404]';
                            } else if (jqXHR.status == 500) {
                                msg = 'Error del Servidor [500].';
                            } else if (exception === 'parsererror') {
                                msg = 'Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                msg = 'Time out error.';
                            } else if (exception === 'abort') {
                                msg = 'Ajax request aborted.';
                            } else {
                                msg = 'Uncaught Error.\n' + jqXHR.responseText;
                            }
                            //$('#post').html(msg);
                            alert(msg);
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
          url: "{{ url('clasificacionclientes') }}" + '/' + id + "/edit",
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Editar tipo de cliente');

            $('#id').val(data.id);
            $('#codigo').val(data.codigo);
            $('#nombre').val(data.nombre);
          },
          error : function() {
            //alert('Ha ocurrido un error, favor verificar');
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Sin Conexion.\n Verifique la red.';
            } else if (jqXHR.status == 404) {
                msg = 'Pagina no encontradad.[404]';
            } else if (jqXHR.status == 500) {
                msg = 'Error del Servidor [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            //$('#post').html(msg);
            alert(msg);
          }
        });
      }

      function deleteData(id){
            var popup = confirm("desea eliminar el registro?");
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            if (popup == true ){
                $.ajax({
                    url : "{{ url('clasificacionclientes') }}" + '/' + id,
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
