@extends('home')

@section('content')

  <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Motivos de Anulación
                        @can('sucursales.create')
                          <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @endcan
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="motivos-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th width="110">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    @include('motivoAnulacion.form')
@endsection

@section('ajax_datatables')
  <script type="text/javascript">
      var table = $('#motivos-table').DataTable({
                      language: { url: '/datatables/translation/spanish' },
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.motivos.anulacion.index') }}",
                      columns: [
                        {data: 'nombre', name: 'nombre'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

    function addForm() {
        save_method = "add";
        $('#error-block').hide();
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Agregar Motivo de Anulación');
    }

    $(function(){
        $('#modal-form form').validator().on('submit', function (e) {
            if (!e.isDefaultPrevented()){
                var id = $('#id').val();
                if (save_method == 'add') url = "{{ url('motivoAnulacion') }}";
                else url = "{{ url('motivoAnulacion') . '/' }}" + id;

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
            url: "{{ url('motivoAnulacion') }}" + '/' + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Editar Motivo de Anulación');

            $('#id').val(data.id);
            $('#nombre').val(data.nombre);
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
            theme: 'modern',
            buttons: {   
                ok: {
                    text: "Eliminar",
                    btnClass: 'btn-danger',
                    keys: ['enter'],
                    action: function(){
                        var csrf_token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url : "{{ url('motivoAnulacion') }}" + '/' + id,
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