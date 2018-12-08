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
                                <th width="80">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
@include('anulacionComprobante.formAnulacionFactura')
@include('anulacionComprobante.formAnulacionNotaCredito')
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
            "className": "text-left",},
            {"targets": 1,
            "className": "text-center",},
            {"targets": 2, "className": "text-center",},
            {"targets": 4, "className": "text-center",},
            {"targets": 5, "className": "text-center",},
            {"targets": 6, "className": "text-center",
        }],
        columns: [
            {data: 'tipo_comp', name: 'tipo_comp'},
            {data: 'nro_comp', name: 'nro_comp'},
            {data: 'fecha_emision', name: 'fecha_emision'},
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

    function showFactura(id) {
        window.location="{{ url('facturacionVentas') }}" + '/' + id;
    }

    function showNotaCredito(id) {
        window.location="{{ url('notaCreditoVentas') }}" + '/' + id;
    }

    function anularFactura(id) {
        $('#error-block').hide();
        $('input[name=_method]').val('POST');
        
        $('#modal-form-factura').modal('show');
        $('#modal-form-factura form')[0].reset();
        $('.modal-title').text('Motivo de anulación - Factura');
        $('#tipo_comprobante_fact').val("F");
        $('#comprobante_id_fact').val(id);
    }

    function anularNotaCredito(id) {
        $('#error-block-nota-cred').hide();
        $('input[name=_method]').val('POST');
        $('#modal-form-nota').modal('show');
        $('#modal-form-nota form')[0].reset();
        $('#tipo_comprobante').val("N");
        $('#comprobante_id').val(id);
        $('.modal-title').text('Motivo de anulación - Nota de Crédito');
    }

    $('#select2-motivos-fact').select2({
        placeholder: 'Seleccione una opción',
        language: "es",
        ajax: {
            url: "{{ route('api.motivos.anulacion') }}",
            delay: 250,
            data: function (params) {
                var queryParameters = {
                    q: params.term
                }
                return queryParameters;
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#select2-motivos-nota').select2({
        placeholder: 'Seleccione una opción',
        language: "es",
        ajax: {
            url: "{{ route('api.motivos.anulacion') }}",
            delay: 250,
            data: function (params) {
                var queryParameters = {
                    q: params.term
                }
                return queryParameters;
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
</script>    
@endsection
