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
                    <label class="col-md-3">Orden de Compra Nro {{$orden_compra->nro_orden}}</label>
                    <label class="col-md-1 "><p class="text-center"> Estado {{$orden_compra->estado}} </p></label>
                </div>
                <div class="form-group">
                    <label class="col-md-3">Proveedor: {{$orden_compra->proveedor}}</label>
                </div>
                <div class="form-group">
                    <label class="col-md-3">Fecha: {{$orden_compra->fecha_emision}}</label>
                </div>
                <div class="form-group">
                    <label class="col-md-3" >Moneda: {{$orden_compra->moneda}}</label>
                    <label class="col-md-4" >Valor Cambio: {{$orden_compra->valor_cambio}}</label>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="active">
                            <th class="text-center" width="20%">Código</th>
                            <th class="text-center">Artículo</th>
                            <th class="text-center" width="8%">Cant.</th>
                            <th class="text-center" width="10%">Costo Unitario</th>
                            <th class="text-center" width="10%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orden_compra_detalle as $detalle)
                            <tr>
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
                            <td class="text-right"><strong>{{$orden_compra->monto_total}}</strong></td>
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