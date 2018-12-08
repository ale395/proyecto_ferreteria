@extends('home')

@section('content')

  <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Comprobantes</h4>
                </div>
                <div class="panel-body">
                    <table id="pedidos-table" class="table-striped table-responsive row-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>Tipo Comp.</th>
                                <th width="100">Nro Comp.</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th width="50">Acciones</th>
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
        ajax: "{{ route('api.comprobantes.ventas') }}",
        'columnDefs': [
            {"targets": 0,
            "className": "text-center",},
            {"targets": 1,
            "className": "text-center",},
            {"targets": 2, "className": "text-center",},
            {"targets": 4, "className": "text-center",},
            {"targets": 5, "className": "text-right",},
            {"targets": 6, "className": "text-center",
        }],
        columns: [
            {data: 'tipo_comp', name: 'tipo_comp'},
            {data: 'nro_comp', name: 'nro_comp'},
            {data: 'fecha', name: 'fecha'},
            {data: 'cliente', name: 'cliente'},
            {data: 'monto_total', name: 'monto_total'},
            {data: 'estado', name: 'estado'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        "order": [[ 1, "desc" ]],
    });

    $('#pedidos-table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })

    function showForm(id) {
        window.location="{{ url('facturacionVentas') }}" + '/' + id;
    }

    function editForm(id) {
        window.location="{{ url('facturacionVentas') }}" + '/' + id +'/edit';
    }
</script>    
@endsection
