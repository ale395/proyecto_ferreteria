@extends('home')

@section('content')

  <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            {{ session('warning') }}
                        </div>
                    @endif
                    <h4>Ordenes de Compra
                        @can('ordencompra.create')
                          <a class="btn btn-primary pull-right" href="{{route('ordencompra.create')}}" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo</a>
                        @endcan
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="orden-compra-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th width="50">Nro Orden</th>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th width="80">Moneda</th>
                                <th>Total Pedido</th>
                                <th width="110">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

@endsection

@section('ajax_datatables')
  <script type="text/javascript">
      var table = $('#orden-compra-table').DataTable({
                      language: { url: 'datatables/translation/spanish' },
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.ordencompra') }}",
                      columns: [
                        {data: 'nro_orden', name: 'codigo'},
                        {data: 'fecha', name: 'fecha'},
                        {data: 'proveedor', name: 'proveedor'},
                        {data: 'codigo', name: 'codigo'},
                        {data: 'monto_total', name: 'monto_total'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

     $('#pedidos-table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
      })

      function showForm(id) {
        window.location="{{ url('ordencompra') }}" + '/' + id;
      }

      function editForm(id) {
        window.location="{{ url('ordencompra') }}" + '/' + id +'/edit';
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
                                  url : "{{ url('ordencompra') }}" + '/' + id,
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
    $('#modal-form').on('shown.bs.modal', function() {
      $('#codigo').focus();
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