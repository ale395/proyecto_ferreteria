@extends('home')

@section('content')

  <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Ajustes
                        @can('ajustesInventarios.create')
                          <a onclick="window.location='{{route('ajustesInventarios.create')}}'" class="btn btn-primary pull-right" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @endcan
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="pedidos-table" class="table-striped table-responsive row-border" style="width:100%">
                        <thead>
                            <tr>
                                <th width="100">Nro Ajuste</th>
                                <th>Fecha</th>
                                <th>Sucursal</th>
                                <th>Concepto Ajuste</th>
 
                                <th>Total</th>
                                <th width="150">Acciones</th>
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
                      ajax: "{{ route('api.ajustes.inventarios') }}",
                      'columnDefs': [
                        {
                            "targets": 0,
                            "className": "text-center",
                       },
                       {
                            "targets": 1,
                            "className": "text-center",
                       },
                       {
                            "targets": 2,
                            "className": "text-center",
                       },
                       {
                            "targets": 3,
                            "className": "text-center",
                       },
                       {
                            "targets": 4,
                            "className": "text-center",
                       }],
                      columns: [
                        {data: 'nro_ajuste', name: 'nro_ajuste'},
                        {data: 'fecha', name: 'fecha'},
                        {data: 'sucursal', name: 'sucursal'},
                        {data: 'concepto_ajuste', name: 'concepto_ajuste'},
                        {data: 'monto_total', name: 'monto_total'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ],
                      "order": [[ 1, "desc" ]],
                    });

      $('#pedidos-table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
      })

      function showForm(id) {
        window.location="{{ url('ajustesInventarios') }}" + '/' + id;
      }

      function editForm(id) {
        window.location="{{ url('ajustesInventarios') }}" + '/' + id +'/edit';
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
                                  url : "{{ url('ajustesInventarios') }}" + '/' + id,
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
