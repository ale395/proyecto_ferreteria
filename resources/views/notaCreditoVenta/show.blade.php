@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="#" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Ver Nota de Crédito
                    <div class="pull-right btn-group">
                        <a data-toggle="tooltip" data-placement="top" title="Imprimir Nota de Crédito" href="{{route('notas.credito.ventas.impresion', $ncre_cab->getId)}}" type="button" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i></a>
                        <a data-toggle="tooltip" data-placement="top" title="Volver al Listado" href="{{route('notaCreditoVentas.index')}}" type="button" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
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
                    <input type="hidden" id="id" name="id" value="{{$ncre_cab->getId()}}">
                    <div class="form-group">
                        <label for="tipo_nota_credito" class="col-md-1 control-label">Tipo</label>
                        <div class="col-md-2">
                            <input type="text" id="tipo_nota_credito" name="tipo_nota_credito" class="form-control" readonly="readonly" value="{{$ncre_cab->getTipoNotaCreditoIndex()}}">
                        </div>
                        <label for="nro_nota_credito" class="col-md-1 control-label">Número</label>
                        <div class="col-md-3">
                            <input type="text" id="nro_nota_credito" name="nro_nota_credito" class="form-control" readonly="readonly" value="{{$ncre_cab->getNroNotaCreditoIndex()}}">
                        </div>
                        <label for="fecha_emision" class="col-md-2 control-label">Fecha</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{$ncre_cab->getFechaEmision()}}" data-inputmask="'mask': '99/99/9999'" readonly>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="cliente_id" class="col-md-1 control-label">Cliente</label>
                        <div class="col-md-6">
                            <input id="select2-clientes" name="cliente_id" class="form-control" value="{{$ncre_cab->cliente->getNombreSelect()}}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="moneda_id" class="col-md-1 control-label">Moneda</label>
                        <div class="col-md-3">
                            <input id="select2-monedas" name="moneda_id" class="form-control" value="{{$ncre_cab->moneda->getDescripcion()}}" readonly>
                        </div>
                        <label for="valor_cambio" class="col-md-1 control-label">Cambio</label>
                        <div class="col-md-2">
                            <input type="text" id="valor_cambio" name="valor_cambio" class="form-control" value="{{$ncre_cab->getValorCambio()}}" readonly>
                        </div>
                         <label for="comentario" class="col-md-1 control-label">Comentario</label>
                        <div class="col-md-4">
                            <textarea class="form-control" rows="2" id="comentario" name="comentario" readonly>{{$ncre_cab->getComentario()}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="estado" class="col-md-1 control-label">Estado</label>
                        <div class="col-md-2">
                            <input type="text" id="estado" name="estado" class="form-control" value="{{old('valor_cambio', $ncre_cab->getEstadoNombre())}}" readonly>
                        </div>
                        <label for="nro_factura" class="col-md-2 control-label">Factura</label>
                        <div class="col-md-2">
                            <input type="text" id="nro_factura" name="nro_factura" class="form-control" value="{{old('nro_factura', $ncre_cab->factura->getNroFacturaIndex())}}" readonly>
                        </div>
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
                            @foreach ($ncre_cab->notaCreditoDetalle as $nota_cred_det)
                                <tr>
                                    <td>{{$nota_cred_det->articulo->getNombreSelect()}}</td>
                                    <td>{{$nota_cred_det->getCantidadNumber()}}</td>
                                    <td>{{$nota_cred_det->getPrecioUnitario()}}</td>
                                    <td>{{$nota_cred_det->getMontoDescuento()}}</td>
                                    <td>{{$nota_cred_det->getMontoExenta()}}</td>
                                    <td>{{$nota_cred_det->getMontoGravada()}}</td>
                                    <td>{{$nota_cred_det->getMontoIva()}}</td>
                                    <td>{{$nota_cred_det->getMontoTotal()}}</td>
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
                                <th>{{$ncre_cab->getMontoTotal()}}</th>
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