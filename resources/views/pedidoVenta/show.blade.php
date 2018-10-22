@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="#" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Ver Pedido
                    <div class="pull-right btn-group">
                        <a data-toggle="tooltip" data-placement="top" title="Imprimir Pedido" href="#" type="button" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i></a>
                        <a data-toggle="tooltip" data-placement="top" title="Volver al Listado" href="{{route('pedidosVentas.index')}}" type="button" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                    </div>
                    
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                    <input name="_method" type="hidden" value="POST">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="hidden" id="id" name="id" value="{{$pedido_cab->getId()}}">
                    <div class="form-group">
                        <label for="nro_pedido" class="col-md-1 control-label">Número</label>
                        <div class="col-md-2">
                            <input type="number" id="nro_pedido" name="nro_pedido" class="form-control" readonly="readonly" value="{{$pedido_cab->getNroPedido()}}">
                        </div>
                        <label for="fecha_emision" class="col-md-5 control-label">Fecha</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{$pedido_cab->getFechaEmision()}}" data-inputmask="'mask': '99/99/9999'" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_id" class="col-md-1 control-label">Cliente</label>
                        <div class="col-md-6">
                            <input id="select2-clientes" name="cliente_id" class="form-control" value="{{$pedido_cab->cliente->getNombreIndex()}}" readonly>
                        </div>
                        <label for="lista_precio_id" class="col-md-1 control-label">Lista Prec.</label>
                        <div class="col-md-3">
                            <a data-toggle="tooltip" data-placement="top" title="Lista de Precios">
                                <input id="select2-lista-precios" name="lista_precio_id" class="form-control" value="{{$pedido_cab->listaPrecio->getNombre()}}" readonly>
                            </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="moneda_id" class="col-md-1 control-label">Moneda</label>
                        <div class="col-md-3">
                            <input id="select2-monedas" name="moneda_id" class="form-control" value="{{$pedido_cab->moneda->getDescripcion()}}" readonly>
                        </div>
                        <label for="valor_cambio" class="col-md-1 control-label">Cambio</label>
                        <div class="col-md-2">
                            <input type="text" id="valor_cambio" name="valor_cambio" class="form-control" value="{{$pedido_cab->getValorCambio()}}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        
                    </div>
                    <br>
                    <table id="pedido-detalle" class="table table-striped table-responsive display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Artículo</th>
                                <th>Cant.</th>
                                <th>Precio U.</th>
                                <th>Descuento</th>
                                <th>Exenta</th>
                                <th>Gravada</th>
                                <th>IVA</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedido_cab->pedidosDetalle as $pedido_det)
                                <tr>
                                    <td>{{$pedido_det->articulo->getDescripcion()}}</td>
                                    <td>{{$pedido_det->getCantidad()}}</td>
                                    <td>{{$pedido_det->getPrecioUnitario()}}</td>
                                    <td>{{$pedido_det->getMontoDescuento()}}</td>
                                    <td>{{$pedido_det->getMontoExenta()}}</td>
                                    <td>{{$pedido_det->getMontoGravada()}}</td>
                                    <td>{{$pedido_det->getMontoIva()}}</td>
                                    <td>{{$pedido_det->getMontoTotal()}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th>{{$pedido_cab->getMontoTotal()}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('otros_scripts')
<script type="text/javascript">
    $('#pedido-detalle').DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
        language: { url: '/datatables/translation/spanish' },
        "columnDefs": [
          { className: "dt-center", "targets": [1,2,3,4,5,6,7] },
          { className: "dt-left", "targets": [0] }
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
                .column(7)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 7 ).footer() ).html(
                $.number(total,decimales, ',', '.')
            );
        }
    });
</script>
@endsection