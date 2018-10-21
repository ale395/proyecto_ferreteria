@extends('home')

@section('content')

  <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Pedidos
                        @can('pedidosVentas.create')
                          <a onclick="window.location='{{route('pedidosVentas.create')}}'" class="btn btn-primary pull-right" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @endcan
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="pedidos-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th width="70">Nro Pedido</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Moneda</th>
                                <th>Total</th>
                                <th>Estado</th>
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
      var table = $('#pedidos-table').DataTable({
                      language: { url: '/datatables/translation/spanish' },
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.pedidos.ventas') }}",
                      'columnDefs': [
                        {
                            "targets": 0, // your case first column
                            "className": "text-center",
                       },
                       {
                            "targets": 1, // your case first column
                            "className": "text-center",
                       },
                       {
                            "targets": 3,
                            "className": "text-center",
                       },
                       {
                            "targets": 4,
                            "className": "text-right",
                       },
                       {
                            "targets": 5,
                            "className": "text-center",
                       }],
                      columns: [
                        {data: 'nro_pedido', name: 'nro_pedido'},
                        {data: 'fecha', name: 'fecha'},
                        {data: 'cliente', name: 'cliente'},
                        {data: 'moneda', name: 'moneda'},
                        {data: 'monto_total', name: 'monto_total'},
                        {data: 'estado', name: 'estado'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

      function editForm(id) {
        window.location="{{ url('pedidosVentas') }}" + '/' + id +'/edit';
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
                                  url : "{{ url('pedidosVentas') }}" + '/' + id,
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
