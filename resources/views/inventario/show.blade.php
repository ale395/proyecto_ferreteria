@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="#" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Ver Inventario
                    <div class="pull-right btn-group">
                        <a data-toggle="tooltip" data-placement="top" title="Imprimir Inventario"  href="{{route('inventarios.impresion', $inventario_cab->getId())}}" type="button" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i></a>
                        <a data-toggle="tooltip" data-placement="top" title="Volver al Listado" href="{{route('inventarios.index')}}" type="button" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
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
                    <input type="hidden" id="id" name="id" value="{{$inventario_cab->getId()}}">
                    <div class="form-group">

                        <label for="nro_inventario" class="col-md-1 control-label">Número</label>
                        <div class="col-md-2">
                            <input type="text" id="nro_inventario" name="nro_inventario" class="form-control" readonly="readonly" value="{{$inventario_cab->getId()}}">
                        </div>
                        <label for="fecha_emision" class="col-md-2 control-label">Fecha</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{$inventario_cab->getFechaEmision()}}" data-inputmask="'mask': '99/99/9999'" readonly>
                        </div>
                        <label for="usuario" class="col-md-2 control-label">Registrado por</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control" id="usuario" name="usuario" readonly value="{{$inventario_cab->usuario->getName()}}">
                        </div>
                    </div>
                    <br>

                    <div class="form-group">
                        <label for="sucursal_id" class="col-md-1 control-label">Sucursal</label>
                        <div class="col-md-3">
                            <input id="select2-sucursales" name="sucursal_id" class="form-control" value="{{$inventario_cab->sucursal->getNombre()}}" readonly>
                        </div>
                         <label for="motivo" class="col-md-1 control-label">Observacion</label>
                        <div class="col-md-4">
                            <textarea class="form-control" rows="2" id="motivo" name="motivo" readonly>{{$inventario_cab->getMotivo()}}</textarea>
                        </div>
                    </div>
                    <br>
                    <table id="pedido-detalle" class="table table-striped table-responsive display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Artículo</th>
                                <th>Existencia</th>
                                <th>Cant.</th>
                                <th>Diferencia.</th>
                                <th>Precio U.</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventario_cab->inventarioDetalle as $inventario_det)
                                <tr>
                                    <td>{{$inventario_det->articulo->getNombreSelect()}}</td>
                                    <td>{{$inventario_det->getExistencia()}}</td>
                                    <td>{{$inventario_det->getCantidad()}}</td>
                                    <td>{{$inventario_det->getDiferencia()}}</td>
                                    <td>{{$inventario_det->getCostoUnitario()}}</td>
                                    <td>{{$inventario_det->getSubTotal()}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total Inventariado</th>
                                <th>{{$inventario_cab->getMontoTotal()}}</th>
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
          { className: "dt-center", "targets": [1,2,3,4,5] },
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