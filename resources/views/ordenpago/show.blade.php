<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body class="nav-md">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="text-center">{{ config('app.name') }}</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3">Orden de Pago Nro {{$orden_pago->nro_orden}}</label>
                    <label class="col-md-1 "><p class="text-center"> Estado {{$orden_pago->estado}} </p></label>
                </div>
                <div class="form-group">
                    <label class="col-md-3">Proveedor: {{$orden_pago->proveedor}}</label>
                </div>
                <div class="form-group">
                    <label class="col-md-3">Fecha: {{$orden_pago->fecha_emision}}</label>
                </div>
                <div class="form-group">
                    <label class="col-md-3" >Moneda: {{$orden_pago->moneda}}</label>
                    <label class="col-md-4" >Valor Cambio: {{$orden_pago->valor_cambio}}</label>
                </div>
                <br>
                <div class="form-group">
                    <label class="col-md-3" >Facturas Pagadas</label>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="active">
                            <th class="text-center" width="20%">Factura</th>
                            <th class="text-center" width="10%">Importe Factura</th>
                            <th class="text-center" width="10%">Importe Afectado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orden_pago_facturas as $detalle)
                            <tr>
                                <td>{{$detalle->compra}}</td>
                                <td class="text-right">{{$detalle->importe_factura}}</td>
                                <td class="text-right">{{$detalle->importe_afectado}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>TOTAL</strong></td>
                            <td class="text-right"><strong>{{$orden_pago->monto_total}}</strong></td>
                        </tr>
                    </tfoot>
                </table>
                <br>
                <div class="form-group">
                    <label class="col-md-3">Cheques</label>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="active">
                            <th class="text-center">Banco</th>
                            <th class="text-center" width="8%">Cuenta Nro</th>
                            <th class="text-center" width="8%">Librador</th>
                            <th class="text-center" width="8%">Moneda</th>
                            <th class="text-center" width="8%">Fecha Emisión</th>
                            <th class="text-center" width="10%">Fecha Vencimiento</th>
                            <th class="text-center" width="10%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orden_pago_cheques as $detalle)
                            <tr>
                                <td>{{$detalle->banco}}</td>
                                <td>{{$detalle->nro_cuenta}}</td>
                                <td>{{$detalle->librador}}</td>
                                <td>{{$detalle->codigo_articulo}}</td>
                                <td>{{$detalle->articulo}}</td>
                                <td class="text-center">{{$detalle->cantidad}}</td>
                                <td class="text-right">{{$detalle->costo_unitario}}</td>
                                <td class="text-right">{{$detalle->sub_total}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"><strong>TOTAL</strong></td>
                            <td class="text-right"><strong>{{$orden_pago->monto_total}}</strong></td>
                        </tr>
                    </tfoot>
                </table>
                <br>
                <br>
                <div class="form-group">
                    <label><p class="text-center">______________________</p></label>
                    <br>
                    <label><p class="text-center"> Autorizado Por</p></label>
                </div>
            </div>
        </div>
    </div>
</div>
</body>