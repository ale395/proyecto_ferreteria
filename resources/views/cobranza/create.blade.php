@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('CobranzaController@store')}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Cobranza
                    <div class="pull-right btn-group">
                        <button data-toggle="tooltip" data-placement="top" title="Guardar" type="submit" class="btn btn-primary btn-save"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                        <a data-toggle="tooltip" data-placement="top" title="Cancelar carga" href="{{route('cobranza.create')}}" type="button" class="btn btn-warning"><i class="fa fa-ban" aria-hidden="true"></i></a>
                        <a data-toggle="tooltip" data-placement="top" title="Volver al Listado" href="{{route('pedidosVentas.index')}}" type="button" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                    </div>
                    
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                    <input name="_method" type="hidden" value="POST">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="fecha" class="col-md-1 control-label">Fecha *</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha" name="fecha" class="form-control" placeholder="dd/mm/aaaa" value="{{old('fecha', $fecha_actual)}}" data-inputmask="'mask': '99/99/9999'" readonly>
                        </div>
                        <label for="moneda_select" class="col-md-1 control-label">Moneda *</label>
                        <div class="col-md-2">
                            <select id="select2-monedas" name="moneda_select" class="form-control" style="width: 100%">
                                <option value="{{$moneda->getId()}}">{{$moneda->getDescripcion()}}</option>
                            </select>
                        </div>
                        <label for="comentario" class="col-md-1 control-label">Comentario</label>
                        <div class="col-md-5">
                            <textarea class="form-control" rows="2" id="comentario" name="comentario"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_id" class="col-md-1 control-label">Cliente *</label>
                        <div class="col-md-5">
                            <select id="select2-clientes" name="cliente_id" class="form-control" autofocus style="width: 100%"></select>
                        </div>
                        <div class="btn-group col-md-4" role="group">
                            <a id="btn-contado" onclick="addFormContado()" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Seleccionar Facturas Contado">Cuenta Contado <i class="fa fa-search" aria-hidden="true"></i></a>
                            <a id="btn-credito" onclick="addFormCredito()" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Seleccionar Facturas Crédito">Cuenta Crédito <i class="fa fa-search" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <input type="hidden" name="moneda_id" value="{{$moneda->getId()}}">
                    <table id="cobranza-comp" class="table table-striped table-responsive display" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="10%">Tipo Comp.</th>
                                <th class="text-center" width="15%">Fecha Emisión</th>
                                <th class="text-center" width="15%">Nro. Comp.</th>
                                <th class="text-center" width="9%">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th class="total">0</th>
                            </tr>
                        </tfoot>
                    </table>
                    <br>
                    <div class="form-group">
                        <label for="forma_pago" class="col-md-1 control-label">Pago</label>
                        <div class="col-md-2">
                            <a data-toggle="tooltip" data-placement="top" title="Forma de pago"><select id="select2-pagos" name="forma_pago" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($pagos as $id => $pago)
                                    <option value="{{ $pago->codigo }}">{{ $pago->descripcion }}</option>
                                @endforeach
                            </select></a>
                        </div>
                        <div class="col-md-2">
                            <a data-toggle="tooltip" data-placement="top" title="Banco">
                            <select id="select2-bancos" name="forma_pago" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($bancos as $id => $banco)
                                    <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                                @endforeach
                            </select></a>
                        </div>
                        <div class="col-md-2">
                            <a data-toggle="tooltip" data-placement="top" title="Fecha de Emisión">
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'"></a>
                        </div>
                        <div class="col-md-2">
                            <a data-toggle="tooltip" data-placement="top" title="N° Cheque o tarjeta"><input type="text" class="form-control" id="nume_valo" name="nume_valo"></a>
                        </div>
                        <div class="col-md-2">
                            <a data-toggle="tooltip" data-placement="top" title="Monto pagado"><input type="text" class="form-control" id="importe" name="importe"></a>
                        </div>
                        <div class="col-md-1">
                            <a id="btn-add-pago" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Añadir pago" onclick="agregarPago()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <br>
                    <table id="cobranza-deta" class="table table-striped table-responsive display" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="10%">Forma Pago</th>
                                <th class="text-center" width="10%">Banco</th>
                                <th class="text-center" width="15%">Fecha Emisión</th>
                                <th class="text-center" width="15%">Nro. Valor</th>
                                <th class="text-center" width="10%">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th class="total">0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
@include('cobranza.formContado')
@include('cobranza.formCredito')
@endsection
@section('otros_scripts')
<script type="text/javascript">
    var total_comprobantes = 0;
    var saldo_pago = 0;
    $("#btn-contado").attr("disabled", false);
    $("#btn-credito").attr("disabled", false);
    $("#btn-add-pago").attr("disabled", true);

    var table = $('#cobranza-comp').DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
        language: { url: '/datatables/translation/spanish' },
        "columnDefs": [
          { className: "dt-center", "targets": [1,3] },
          { className: "dt-left", "targets": [0,2] }
        ],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
            var decimales = 0;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(".", "")*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column(3)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 3 ).footer() ).html(
                $.number(total,decimales, ',', '.')
            );
        }
    });

    var tabla_det = $('#cobranza-deta').DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
        language: { url: '/datatables/translation/spanish' },
        "columnDefs": [
          { className: "dt-center", "targets": [3,4] },
          { className: "dt-left", "targets": [0,1,2] }
        ],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
            var decimales = 0;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(".", "")*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column(4)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 4 ).footer() ).html(
                $.number(total,decimales, ',', '.')
            );
        }
    });

    function addFormContado(){
        if ($('#select2-clientes').val() == null) {
            var obj = $.alert({
                title: 'Atención',
                content: 'Debe seleccionar el cliente para buscar su cuenta!',
                icon: 'fa fa-exclamation-triangle',
                type: 'orange',
                backgroundDismiss: true,
                theme: 'modern',
            });
            setTimeout(function(){
                obj.close();
            },3000); 
        } else {
            if ($.fn.DataTable.isDataTable('#tabla-cuenta-contado')) {
                $('#tabla-cuenta-contado').DataTable().clear();
                $('#tabla-cuenta-contado').DataTable().destroy();    
            }

            tablePedidos = $('#tabla-cuenta-contado').DataTable({
                
                language: { url: '/datatables/translation/spanish' },
                processing: true,
                serverSide: true,
                ajax: {"url": "/api/facturacionVentas/contado/cliente/"+$('#select2-clientes').val()},
                select: {
                    style: 'multi'
                },
                columns: [
                    {data: 'nro_factura'},
                    {data: 'fecha'},
                    {data: 'monto_total'},
                    {data: 'comentario'}
                    ],
                'columnDefs': [
                { className: "dt-center", "targets": [1,2,3]}],
                "order": [[ 1, 'asc' ], [ 0, 'asc' ]]
            });
            
            $('#modal-cuenta-contado').modal('show');
            $('#modal-cuenta-contado form')[0].reset();
            $('.modal-title').text('Lista de Facturas Contado');
        }
    }

    function addFormCredito(){
        if ($('#select2-clientes').val() == null) {
            var obj = $.alert({
                title: 'Atención',
                content: 'Debe seleccionar el cliente para buscar su cuenta!',
                icon: 'fa fa-exclamation-triangle',
                type: 'orange',
                backgroundDismiss: true,
                theme: 'modern',
            });
            setTimeout(function(){
                obj.close();
            },3000); 
        } else {
            if ($.fn.DataTable.isDataTable('#tabla-cuenta-credito')) {
                $('#tabla-cuenta-credito').DataTable().clear();
                $('#tabla-cuenta-credito').DataTable().destroy();    
            }

            tablePedidos = $('#tabla-cuenta-credito').DataTable({
                
                language: { url: '/datatables/translation/spanish' },
                processing: true,
                serverSide: true,
                ajax: {"url": "/api/facturacionVentas/credito/cliente/"+$('#select2-clientes').val()},
                columns: [
                    {data: 'nro_factura'},
                    {data: 'fecha'},
                    {data: 'monto_total'},
                    {data: 'comentario'}
                    ],
                'columnDefs': [
                { className: "dt-center", "targets": [1,2,3] }],
                "order": [[ 1, 'asc' ], [ 0, 'asc' ]]
            });
            
            $('#modal-cuenta-credito').modal('show');
            $('#modal-cuenta-credito form')[0].reset();
            $('.modal-title').text('Lista de Facturas Crédito');
            $('#monto_total').number(true, 0, ',', '.');
            $('#modal-cuenta-credito').on('shown.bs.modal', function() {
                $("#monto_total").focus();
            });
        }
    }

    function cargarFacturas(){
        var datos = tablePedidos.rows( { selected: true } ).data();
        var array_pedidos = [];
        for (i = 0; i < datos.length; i++) {
            array_pedidos.push(datos[i].id);
            table.row.add([
                "Factura Contado",
                datos[i].fecha,
                datos[i].nro_factura,
                datos[i].monto_total
            ]).draw( false );
            total_comprobantes = Number(total_comprobantes) + Number(datos[i].monto_total.replace(".",""));
        }
        saldo_pago = total_comprobantes;
        $('#modal-cuenta-contado').modal('hide');
        $("#btn-contado").attr("disabled", true);
        $("#btn-credito").attr("disabled", true);
    }

    function cargarFacturasCredito(){
        var monto_total = $('#monto_total').val();
        var saldo = monto_total;
        var datos_monto = 0;
        var datos = tablePedidos.rows().data();
        if (monto_total > 0) {
            for (i = 0; i < datos.length; i++) {
                //console.log(datos[i]);
                datos_monto = datos[i].monto_total.replace(".", "");
                if (Number(saldo) > Number(datos_monto)) {
                    saldo = Number(saldo) - Number(datos_monto);
                    table.row.add([
                        "Factura Crédito",
                        datos[i].fecha,
                        datos[i].nro_factura,
                        datos[i].monto_total
                    ]).draw( false );
                } else {
                    table.row.add([
                        "Factura Crédito",
                        datos[i].fecha,
                        datos[i].nro_factura,
                        $.number(saldo, 0, ',', '.')
                    ]).draw( false );
                }
            }
            $('#modal-cuenta-credito').modal('hide');
            $("#btn-contado").attr("disabled", true);
            $("#btn-credito").attr("disabled", true);
        } else {
            alret('Alerta else');
        }
    }


    $(document).ready(function(){
        $('#select2-clientes').select2({
            placeholder: 'Seleccione una opción',
            language: "es",
            minimumInputLength: 4,
            ajax: {
                url: "{{ route('api.clientes.ventas') }}",
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

        $('#select2-monedas').select2({
            placeholder: 'Seleccione una opción',
            language: "es",
            disabled: true,
            ajax: {
                url: "{{ route('api.monedas.select') }}",
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

        $('#select2-bancos').select2({
            placeholder : 'Seleccione una opción',
            tags: false,
            width: 'resolve',
            language: "es"
        });

        $('#select2-pagos').select2({
            placeholder : 'Seleccione una opción',
            tags: false,
            width: 'resolve',
            language: "es"
        });
    });

    $('#importe').number(true, 0, ',', '.');

    $("#select2-pagos").change(function (e) {
        var valor = $(this).val();
        if (valor != null) {
            if (valor == 'EFE') {
                $("#select2-bancos").prop('disabled', true);
                $("#fecha_emision").prop('disabled', true);
                $("#nume_valo").prop('disabled', true);
                $("#importe").focus();
                $("#importe").val(saldo_pago).change();
                $("#btn-add-pago").attr("disabled", false);
            } else {
                $("#select2-bancos").prop('disabled', false);
                $("#fecha_emision").prop('disabled', false);
                $("#nume_valo").prop('disabled', false);
                $("#select2-bancos").focus();
                $("#btn-add-pago").attr("disabled", false);
            }
        } else {
            alert('else');
        }
    });

    function agregarPago(){
        var formaPago = $('#select2-pagos').val();
        var formaPagoTexto = $('#select2-pagos').select2('data')[0].text;
        var importe = $('#importe').val();
        if (Number(saldo_pago) == 0) {
            var obj = $.alert({
                    title: 'Atención',
                    content: 'El total en formas de pago no puede superar al total de comprobantes seleccionados!',
                    icon: 'fa fa-exclamation-triangle',
                    type: 'orange',
                    backgroundDismiss: true,
                    theme: 'modern',
                });
                setTimeout(function(){
                    obj.close();
                },3000);
        } else {
            if (formaPago == 'EFE') {
                if (importe.length != 0) {
                    if (Number(importe) > Number(saldo_pago)) {
                        var obj = $.alert({
                            title: 'Atención',
                            content: 'El total en formas de pago no puede superar al total de comprobantes seleccionados!',
                            icon: 'fa fa-exclamation-triangle',
                            type: 'orange',
                            backgroundDismiss: true,
                            theme: 'modern',
                        });
                        setTimeout(function(){
                            obj.close();
                        },3000); 
                    } else {
                        tabla_det.row.add([
                            formaPagoTexto,
                            null,
                            null,
                            null,
                            $.number(importe, 0, ',', '.')
                        ]).draw( false );
                        saldo_pago = Number(saldo_pago) - Number(importe.replace(".", ""));
                    }
                } else {
                    var obj = $.alert({
                        title: 'Atención',
                        content: 'Debe ingresar el monto del pago!',
                        icon: 'fa fa-exclamation-triangle',
                        type: 'orange',
                        backgroundDismiss: true,
                        theme: 'modern',
                    });
                    setTimeout(function(){
                        obj.close();
                    },3000); 
                }
            } else {

            }
        }
        $('#importe').val("").change();
        $('#select2-pagos').val(null).trigger('change');
    }
</script>
@endsection