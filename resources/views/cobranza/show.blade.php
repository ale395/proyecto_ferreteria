@extends('home')
@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="post" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Resumen de Cobranza
                    <div class="pull-right btn-group">
                        <a data-toggle="tooltip" data-placement="top" title="Imprimir Ticket" href="{{route('cobranza.impresion', $cabecera->getId())}}" type="button" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i></a>
                        <a data-toggle="tooltip" data-placement="top" title="Nueva Cobranza" href="{{route('cobranza.create')}}" type="button" class="btn btn-info"><i class="fa fa-plus" aria-hidden="true"></i></a>
                        <a data-toggle="tooltip" data-placement="top" title="Ver Listado" href="{{route('cobranza.index')}}" type="button" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
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
                    <div class="form-group">
                        <label for="id" class="col-md-1 control-label">N° Cob.</label>
                        <div class="col-md-2">
                            <input type="text" id="id" name="id" class="form-control" value="{{$cabecera->getId()}}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fecha" class="col-md-1 control-label">Fecha</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha" name="fecha" class="form-control" value="{{$cabecera->getFecha()}}" readonly>
                        </div>
                        <label for="moneda_select" class="col-md-1 control-label">Moneda</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control" id="moneda_id" name="moneda_id" value="{{$cabecera->moneda->getDescripcion()}}" readonly>
                        </div>
                        <label for="comentario" class="col-md-1 control-label">Comentario</label>
                        <div class="col-md-5">
                            <textarea class="form-control" rows="2" id="comentario" name="comentario" readonly>{{$cabecera->getComentario()}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_id" class="col-md-1 control-label">Cliente</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="cliente_id" name="cliente_id" value="{{$cabecera->cliente->getNombreIndex()}}" readonly>
                        </div>
                        <label for="vuelto" class="col-md-1 control-label">Vuelto</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control text-right" id="vuelto" name="vuelto" value="{{$cabecera->getVuelto()}}" readonly>
                        </div>
                    </div>
                    <table id="cobranza-comp" class="table table-striped table-responsive display" style="width:100%">
                        <thead>
                            <tr>
                                <th width="10%">Tipo Comp.</th>
                                <th class="text-center" width="15%">Fecha Emisión</th>
                                <th class="text-center" width="15%">Nro. Comp.</th>
                                <th class="text-center" width="9%">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cabecera->comprobantes as $comprobante)
                                <tr>
                                    <td>Factura {{$comprobante->factura->getTipoFacturaIndex()}}</td>
                                    <td class="text-center">{{$comprobante->factura->getFechaEmision()}}</td>
                                    <td class="text-center">{{$comprobante->factura->getNroFacturaIndex()}}</td>
                                    <td class="text-center">{{$comprobante->getMontoIndex()}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th class="text-center">{{number_format($cabecera->comprobantes->sum('monto'), 0, ',', '.')}}</th>
                            </tr>
                        </tfoot>
                    </table>
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
                            @foreach ($cabecera->pagos as $pago)
                                <tr>
                                    <td>{{$pago->formaPago->descripcion}}</td>
                                    @if($pago->formaPago->codigo != 'EFE')
                                        <td class="text-center">{{$pago->banco->getNombre()}}</td>
                                        <td class="text-center">{{$pago->getFechaEmision()}}</td>
                                        <td class="text-center">{{$pago->getNroValor()}}</td>
                                    @else
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif
                                    <td class="text-center">{{$pago->getMontoIndex()}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th class="text-center">{{number_format($cabecera->pagos->sum('monto'), 0, ',', '.')}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection